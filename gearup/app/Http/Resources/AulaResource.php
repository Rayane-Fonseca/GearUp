<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AulaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id_aula,
            'titulo' => $this->titulo,
            'tipo' => $this->tipo,
            'conteudo' => $this->conteudo,
            'url_arquivo' => $this->url_arquivo,
            'duracao' => $this->duracao,
            'ordem' => $this->ordem,
        ];
    }
}