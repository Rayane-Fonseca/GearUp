<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modulo extends Model
{
    protected $table = 'modulos';
    protected $primaryKey = 'id_modulo';

    protected $fillable = [
        'id_curso',
        'titulo',
        'descricao',
        'capa',
        'fundo',
        'ordem',
    ];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }

    public function aulas(): HasMany
    {
        return $this->hasMany(Aula::class, 'id_modulo', 'id_modulo');
    }
}