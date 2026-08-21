<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aula extends Model
{
    use HasFactory;

    protected $table = 'aulas';

    protected $fillable = [
        'id_modulo',
        'titulo',
        'descricao',
        'url_video',
        'duracao',
        'duracao_minutos',
        'ordem',
    ];

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class, 'id_modulo', 'id_modulo');
    }

    public function progressos(): HasMany
    {
        return $this->hasMany(AulaProgresso::class, 'aula_id', 'id');
    }

    /**
     * Método Auxiliar: Retorna o progresso do usuário logado
     */
    public function progressoUsuario(): ?AulaProgresso
    {
        // Se a relação 'progressos' já estiver carregada na memória, busca na Coleção
        if ($this->relationLoaded('progressos')) {
            return $this->progressos->firstWhere('usuario_id', auth()->id());
        }

        // Se não estiver carregada, executa a query no banco filtrando pelo usuário
        return $this->progressos()->where('usuario_id', auth()->id())->first();
    }
}