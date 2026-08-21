<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\AulaProgresso;
use App\Models\Curso;
use Illuminate\Http\Request;

class AulaController extends Controller
{
    /**
     * Exibe o player de video/conteudo da aula com a lista de módulos na sidebar.
     *
     * @param  \App\Models\Curso  $curso
     * @param  \App\Models\Aula   $aula
     * @return \Illuminate\View\View
     */
    public function show(Curso $curso, Aula $aula)
    {
        $usuarioId = auth()->id();

        // Carrega os módulos do curso ordenados, com suas aulas e o progresso
        // do aluno logado em cada uma delas (para os indicadores da sidebar)
        $modulos = $curso->modulos()
            ->with(['aulas' => function ($query) use ($usuarioId) {
                $query->orderBy('ordem', 'asc')
                    ->with(['progressos' => function ($q) use ($usuarioId) {
                        $q->where('usuario_id', $usuarioId);
                    }]);
            }])
            ->orderBy('ordem', 'asc')
            ->get();

        // Identifica a próxima aula e a aula anterior para os botões de navegação
        $todasAulas = $modulos->pluck('aulas')->flatten();
        $indexAtual = $todasAulas->search(fn($item) => $item->id === $aula->id);

        $aulaAnterior = $todasAulas->get($indexAtual - 1);
        $proximaAula = $todasAulas->get($indexAtual + 1);

        // Progresso já salvo do aluno para a aula atual (usado para retomar o vídeo
        // do ponto em que parou e para exibir a barra de progresso da aula)
        $progressoAula = AulaProgresso::where('usuario_id', $usuarioId)
            ->where('aula_id', $aula->id)
            ->first();

        return view('aluno.aulas.show', compact(
            'curso',
            'aula',
            'modulos',
            'aulaAnterior',
            'proximaAula',
            'progressoAula'
        ));
    }
}