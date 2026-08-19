<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrilhaResource extends JsonResource
{
    /**
     * Transforma o recurso em um array.
     */
    public function toArray(Request $request): array
    {
        $usuario = $request->user();
        $cursos = $this->whenLoaded('cursos');

        $progressoAgregado = 0;
        $totalObrigatorios = 0;

        // Se os cursos foram carregados na query e temos um usuário logado
        if ($cursos && $usuario) {
            // Filtra os cursos que possuem o campo 'obrigatorio' como true na tabela pivô
            $obrigatorios = $cursos->filter(function ($curso) {
                return (bool) ($curso->pivot->obrigatorio ?? true);
            });

            $totalObrigatorios = $obrigatorios->count();

            if ($totalObrigatorios > 0) {
                $somaProgressos = 0;
                foreach ($obrigatorios as $curso) {
                    // Reutiliza o cálculo de progresso individual por curso que você já tem na Model de Usuário
                    $somaProgressos += $usuario->progressoNoCurso($curso->id_curso);
                }
                // Média aritmética do progresso dos cursos obrigatórios
                $progressoAgregado = round($somaProgressos / $totalObrigatorios, 1);
            }
        }

        return [
            'id_trilha' => $this->id_trilha,
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'status' => $this->status,
            'progresso_agregado' => $usuario ? $progressoAgregado : null,
            'total_cursos' => $cursos ? $cursos->count() : 0,
            'total_obrigatorios' => $totalObrigatorios,
            
            // Retorna a lista de cursos formatada
            'cursos' => $cursos ? $cursos->map(function ($curso) use ($usuario) {
                return [
                    'id_curso' => $curso->id_curso,
                    'titulo' => $curso->titulo,
                    'categoria' => $curso->categoria,
                    'carga_horaria' => $curso->carga_horaria,
                    'imagem' => $curso->imagem ? asset('storage/' . $curso->imagem) : null,
                    'obrigatorio' => (bool) ($curso->pivot->obrigatorio ?? true),
                    'ordem' => (int) ($curso->pivot->ordem ?? 0),
                    'progresso_usuario' => $usuario ? $usuario->progressoNoCurso($curso->id_curso) : 0,
                ];
            }) : [],
        ];
    }
}