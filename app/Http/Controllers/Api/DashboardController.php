<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();

        // Traz as métricas e dados essenciais para o dashboard do aluno
        return response()->json([
            'usuario' => [
                'id' => $usuario->id,
                'nome' => $usuario->name ?? $usuario->nome,
                'email' => $usuario->email,
            ],
            'estatisticas' => [
                'cursosConcluidos' => $usuario->progressos()->where('concluido', true)->count(),
                'cursosEmAndamento' => $usuario->progressos()->where('concluido', false)->count(),
                'totalCertificados' => method_exists($usuario, 'certificados') ? $usuario->certificados()->count() : 0,
            ],
            'ultimosCursos' => $usuario->progressos()
                ->with('curso')
                ->latest('updated_at')
                ->take(3)
                ->get()
                ->map(function ($progresso) {
                    return [
                        'id' => $progresso->curso->id ?? null,
                        'titulo' => $progresso->curso->titulo ?? '',
                        'progressoPercentual' => $progresso->percentual ?? 0,
                    ];
                }),
        ]);
    }
}