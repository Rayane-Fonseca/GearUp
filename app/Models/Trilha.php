<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Trilha extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'categoria',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function cursos(): BelongsToMany
    {
        return $this->belongsToMany(Curso::class, 'trilha_curso', 'id_trilha', 'id_curso')
            ->withPivot('obrigatorio');
    }
}
