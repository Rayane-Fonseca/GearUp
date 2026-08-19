<?php

namespace App\Http\Controllers;

use App\Models\Aula;
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
        // Carrega os módulos do curso ordenados e com suas respectivas aulas
        $modulos = $curso->modulos()
            ->with(['aulas' => function ($query) {
                $query->orderBy('ordem', 'asc');
            }])
            ->orderBy('ordem', 'asc')
            ->get();

        // Identifica a próxima aula e a aula anterior para os botões de navegação
        $todasAulas = $modulos->pluck('aulas')->flatten();
        $indexAtual = $todasAulas->search(fn($item) => $item->id === $aula->id);

        $aulaAnterior = $todasAulas->get($indexAtual - 1);
        $proximaAula = $todasAulas->get($indexAtual + 1);

        return view('aluno.aulas.show', compact(
            'curso',
            'aula',
            'modulos',
            'aulaAnterior',
            'proximaAula'
        ));
    }
}