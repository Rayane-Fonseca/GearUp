<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;

class AlunoController extends Controller
{
    public function inicio()
    {
        // Conta quantos cursos ativos existem cadastrados pelo Admin
        $cursosAndamentoCount = Curso::where('status', 'Em andamento')->count();

        return view('aluno.inicio', compact('cursosAndamentoCount'));
    }

    public function cursos()
    {
        // Busca todos os cursos reais do banco criados no painel admin
        $cursos = Curso::all(); 

        return view('aluno.cursos', compact('cursos'));
    }
}