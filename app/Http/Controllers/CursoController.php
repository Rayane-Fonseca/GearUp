<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    // Lista todas as categorias com seus respectivos cursos
    public function index()
    {
        $categorias = Categoria::with(['cursos.modulos.aulas.progressos' => function ($query) {
            $query->where('usuario_id', auth()->id());
        }])->get();

        return view('aluno.cursos.index', compact('categorias'));
    }

    // Exibe os detalhes do curso com módulos, aulas e o progresso do usuário
    public function show(Curso $curso)
    {
        $curso->load(['modulos.aulas.progressos' => function ($query) {
            $query->where('usuario_id', auth()->id());
        }]);

        $obrigatorio = $curso->ehObrigatorioPara(auth()->user());

        return view('aluno.cursos.show', compact('curso', 'obrigatorio'));
    }
}