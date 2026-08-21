<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AulaProgresso extends Model
{
    use HasFactory;

    protected $table = 'aula_progressos';

    protected $fillable = [
        'usuario_id',
        'aula_id',
        'curso_id',
        'tempo_assistido',
        'duracao_total',
        'porcentagem',
        'concluido',
        'concluido_em',
    ];

    protected $casts = [
        'concluido' => 'boolean',
        'concluido_em' => 'datetime',
        'tempo_assistido' => 'integer',
        'duracao_total' => 'integer',
        'porcentagem' => 'integer',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id_usuario');
    }

    public function aula(): BelongsTo
    {
        return $this->belongsTo(Aula::class, 'aula_id', 'id');
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'curso_id', 'id_curso');
    }
}
