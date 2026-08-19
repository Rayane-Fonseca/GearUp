<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Curso extends Model
{
    use HasFactory;

    protected $table = 'cursos';
    protected $primaryKey = 'id_curso';

    protected $fillable = [
        'titulo',
        'categoria',
        'descricao',
        'instrutor',
        'carga_horaria',
        'status',
        'capa',
        'fundo',
    ];

    public function modulos(): HasMany
    {
        return $this->hasMany(Modulo::class, 'id_curso', 'id_curso');
    }

    public function trilhas(): BelongsToMany
    {
        return $this->belongsToMany(Trilha::class, 'trilha_curso', 'id_curso', 'id_trilha')
            ->withPivot('obrigatorio');
    }

    public function progressos(): HasMany
    {
        return $this->hasMany(Progresso::class, 'curso_id', 'id_curso');
    }

    public function certificados(): HasMany
    {
        return $this->hasMany(Certificado::class, 'id_curso', 'id_curso');
    }

    public function getRouteKeyName()
    {
        return 'id_curso';
    }

    public const CORES_CATEGORIAS = [
        'DevOps' => '#9B5DE5',
        'Cloud Computing' => '#CA7FB0',
        'Segurança da Informação' => '#00F5D4',
        'Desenvolvimento de Software' => '#F15BB5',
        'Banco de Dados' => '#FEE440',
        'Suporte Técnico' => '#00BBF9',
    ];

    public function getCorCategoriaAttribute(): string
    {
        return self::CORES_CATEGORIAS[$this->categoria] ?? '#6B7280'; // Cor neutra padrão se não encontrar
    }
}