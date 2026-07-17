<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}