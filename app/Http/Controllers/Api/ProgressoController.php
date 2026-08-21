<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\GerarPdfCertificadoJob;
use App\Models\Aula;
use App\Models\AulaProgresso;
use App\Models\Certificado;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Progresso;

class ProgressoController extends Controller
{
    /**
     * Alterna o status de conclusão de uma aula para o usuário autenticado.
     */
    public function toggleAulaConcluida(Request $request): JsonResponse
    {
        // 1. Valida se o ID da aula foi enviado e se ela realmente existe
        $request->validate([
            'id_aula' => 'required|integer|exists:aulas,id_aula',
        ]);

        $usuario = $request->user(); // Obtém o usuário logado (via Sanctum/JWT)
        $idAula = $request->input('id_aula');

        // 2. Busca a aula para descobrir a qual curso ela pertence
        $aula = Aula::with('modulo')->findOrFail($idAula);
        $idCurso = $aula->modulo->id_curso;

        // 3. Faz o "toggle" (insere se não existir, remove se existir) na tabela pivô
        // O método 'toggle' retorna um array mostrando o que foi feito (attached ou detached)
        $resultado = $usuario->aulasConcluidas()->toggle($idAula);

        $foiConcluida = in_array($idAula, $resultado['attached']);

        // 4. Calcula o novo progresso geral do curso para retornar de forma dinâmica
        $novoProgresso = $usuario->progressoNoCurso($idCurso);

        return response()->json([
            'sucesso' => true,
            'mensagem' => $foiConcluida
                ? 'Aula marcada como concluída!'
                : 'Conclusão da aula removida!',
            'dados' => [
                'id_aula' => $idAula,
                'concluida' => $foiConcluida,
                'id_curso' => $idCurso,
                'novo_progresso_curso' => $novoProgresso // O frontend já atualiza a barra de progresso na hora!
            ]
        ]);
    }
    /**
     * Retorna os dados da tela "Início" do aluno com cursos em andamento e progresso.
     */
    public function home(Request $request): JsonResponse
    {
        $usuario = $request->user();

        // 1. Buscamos todos os cursos ativos do sistema
        $cursos = \App\Models\Curso::where('status', 'ativo')->get();

        $cursosEmAndamento = [];
        $cursosConcluidosCount = 0;

        foreach ($cursos as $curso) {
            // Calcula o progresso usando o método que você já tem na Model de Usuário
            $progresso = $usuario->progressoNoCurso($curso->id_curso);

            if ($progresso > 0 && $progresso < 100) {
                // Se o progresso está entre 1% e 99%, o curso está em andamento
                $cursosEmAndamento[] = [
                    'id_curso' => $curso->id_curso,
                    'titulo' => $curso->titulo,
                    'categoria' => $curso->categoria,
                    'imagem' => $curso->imagem ? asset('storage/' . $curso->imagem) : null,
                    'carga_horaria' => $curso->carga_horaria,
                    'progresso' => $progresso,
                ];
            } elseif ($progresso === 100) {
                // Contador simples de quantos ele já finalizou
                $cursosConcluidosCount++;
            }
        }

        return response()->json([
            'sucesso' => true,
            'dados' => [
                'usuario' => [
                    'nome' => $usuario->nome,
                    'email' => $usuario->email,
                ],
                'resumo' => [
                    'total_em_andamento' => count($cursosEmAndamento),
                    'total_concluidos' => $cursosConcluidosCount,
                ],
                'cursos_em_andamento' => $cursosEmAndamento
            ]
        ]);
    }
    /**
     * Salva o progresso de reprodução da aula (tempo assistido) para o aluno logado,
     * permitindo retomar o vídeo de onde parou, e recalcula o progresso real do curso.
     */
    public function atualizarProgresso(Request $request)
    {
        $validated = $request->validate([
            'aula_id'       => 'required|integer|exists:aulas,id',
            'tempo_atual'   => 'required|numeric|min:0', // em segundos
            'duracao_total' => 'required|numeric|min:0', // em segundos
        ]);

        $usuarioId = auth()->id();
        $aula = Aula::with('modulo')->findOrFail($validated['aula_id']);
        $cursoId = $aula->modulo->id_curso ?? null;

        $duracaoTotal = (int) round($validated['duracao_total']);
        $tempoAtual = (int) round($validated['tempo_atual']);
        // Nunca deixa o tempo assistido ultrapassar a duração total do vídeo
        if ($duracaoTotal > 0 && $tempoAtual > $duracaoTotal) {
            $tempoAtual = $duracaoTotal;
        }

        $porcentagem = $duracaoTotal > 0
            ? min(100, (int) round(($tempoAtual / $duracaoTotal) * 100))
            : 0;

        // Considera a aula concluída ao atingir 90% do vídeo (evita travar em segundos finais)
        $novaConclusao = $porcentagem >= 90;

        $aulaProgresso = AulaProgresso::firstOrNew([
            'usuario_id' => $usuarioId,
            'aula_id'    => $aula->id,
        ]);

        // Uma aula já concluída não "desconclui" caso o aluno retroceda o vídeo
        $concluido = $aulaProgresso->concluido || $novaConclusao;

        $aulaProgresso->curso_id = $cursoId;
        $aulaProgresso->tempo_assistido = $tempoAtual;
        $aulaProgresso->duracao_total = $duracaoTotal;
        // Também não deixa a porcentagem exibida retroceder após a aula já ter sido concluída
        $aulaProgresso->porcentagem = $aulaProgresso->concluido ? max($aulaProgresso->porcentagem, $porcentagem) : $porcentagem;
        $aulaProgresso->concluido = $concluido;
        if ($concluido && !$aulaProgresso->concluido_em) {
            $aulaProgresso->concluido_em = now();
        }
        $aulaProgresso->save();

        $resultadoCurso = $cursoId
            ? $this->recalcularProgressoDoCurso($usuarioId, $cursoId)
            : ['porcentagem_curso' => 0, 'curso_concluido' => false, 'certificado_liberado' => false];

        return response()->json([
            'sucesso'              => true,
            'porcentagem'          => $aulaProgresso->porcentagem,
            'concluido'            => $aulaProgresso->concluido,
            'tempo_assistido'      => $aulaProgresso->tempo_assistido,
            'porcentagem_curso'    => $resultadoCurso['porcentagem_curso'],
            'curso_concluido'      => $resultadoCurso['curso_concluido'],
            'certificado_liberado' => $resultadoCurso['certificado_liberado'],
        ]);
    }

    /**
     * Recalcula o progresso consolidado do curso (tabela "progressos") a partir do
     * progresso individual de cada aula e, ao atingir 100%, libera o certificado
     * do aluno imediatamente (sem precisar de solicitação manual).
     */
    private function recalcularProgressoDoCurso(int $usuarioId, int $cursoId): array
    {
        $totalAulas = Aula::whereHas('modulo', function ($query) use ($cursoId) {
            $query->where('id_curso', $cursoId);
        })->count();

        $progressosDasAulas = AulaProgresso::where('usuario_id', $usuarioId)
            ->where('curso_id', $cursoId)
            ->get(['porcentagem', 'concluido']);

        // Soma a porcentagem assistida de cada aula (aulas ainda não iniciadas contam como 0%),
        // assim o progresso do curso já sobe conforme o aluno vai assistindo, sem esperar concluir.
        $somaPorcentagens = $progressosDasAulas->sum('porcentagem');
        $aulasConcluidas = $progressosDasAulas->where('concluido', true)->count();

        $porcentagemCurso = $totalAulas > 0
            ? (int) round($somaPorcentagens / $totalAulas)
            : 0;

        // O curso só é considerado "concluído" (libera certificado) quando TODAS as aulas
        // foram de fato concluídas, mesmo que a porcentagem já esteja exibindo perto de 100%.
        $cursoConcluido = $totalAulas > 0 && $aulasConcluidas >= $totalAulas;
        if ($cursoConcluido) {
            $porcentagemCurso = 100;
        }

        $progresso = Progresso::firstOrNew([
            'usuario_id' => $usuarioId,
            'curso_id'   => $cursoId,
        ]);

        $jaEstavaConcluido = $progresso->exists && $progresso->concluido;

        $progresso->porcentagem = $porcentagemCurso;
        $progresso->concluido = $cursoConcluido;
        if ($cursoConcluido && !$progresso->concluido_em) {
            $progresso->concluido_em = now();
        }
        $progresso->save();

        $certificadoLiberado = false;

        // Libera (gera) o certificado automaticamente assim que o curso é concluído
        if ($cursoConcluido && !$jaEstavaConcluido) {
            $jaTemCertificado = Certificado::where('id_usuario', $usuarioId)
                ->where('id_curso', $cursoId)
                ->exists();

            if (!$jaTemCertificado) {
                GerarPdfCertificadoJob::dispatchSync($usuarioId, $cursoId);
                $certificadoLiberado = true;
            }
        }

        return [
            'porcentagem_curso'    => $porcentagemCurso,
            'curso_concluido'      => $cursoConcluido,
            'certificado_liberado' => $certificadoLiberado,
        ];
    }
}
