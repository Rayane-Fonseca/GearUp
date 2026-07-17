<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trilha;
use App\Http\Resources\TrilhaResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrilhaController extends Controller
{
    /**
     * Retorna todas as trilhas ativas com seus respectivos cursos ordenados.
     */
    public function index(): JsonResponse
    {
        // Carrega as trilhas e ordena os cursos relacionados pela coluna 'ordem' da tabela pivô
        $trilhas = Trilha::where('status', 'ativo')
            ->with(['cursos' => function ($query) {
                $query->orderBy('trilha_curso.ordem', 'asc');
            }])
            ->get();

        return response()->json([
            'sucesso' => true,
            'dados' => TrilhaResource::collection($trilhas)
        ]);
    }

    /**
     * Retorna o detalhe de uma trilha específica.
     */
    public function show($id): JsonResponse
    {
        $trilha = Trilha::with(['cursos' => function ($query) {
            $query->orderBy('trilha_curso.ordem', 'asc');
        }])->find($id);

        if (!$trilha) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Trilha não encontrada.'
            ], 404);
        }

        return response()->json([
            'sucesso' => true,
            'dados' => new TrilhaResource($trilha)
        ]);
    }
}