<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Progresso extends Model
{
    use HasFactory;

    protected $table = 'progressos';

    protected $fillable = [
        'usuario_id',
        'curso_id',
        'aula_id',
        'porcentagem',
        'concluido',
        'concluido_em',
    ];

    protected $casts = [
        'concluido' => 'boolean',
        'concluido_em' => 'datetime',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id_usuario');
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'curso_id', 'id_curso');
    }

    public function aula(): BelongsTo
    {
        return $this->belongsTo(Aula::class, 'aula_id', 'id');
    }

    public function status(): string
    {
        if ($this->porcentagem >= 100) {
            return 'Concluído';
        }

        if ($this->porcentagem > 0) {
            return 'Em andamento';
        }

        return 'Não iniciado';
    }
}
