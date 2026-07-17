<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuloResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id_modulo,
            'titulo' => $this->titulo,
            'ordem' => $this->ordem,
            // Carrega as aulas apenas se o relacionamento tiver sido chamado (evita consultas extras desnecessárias)
            'aulas' => AulaResource::collection($this->whenLoaded('aulas')),
        ];
    }
}