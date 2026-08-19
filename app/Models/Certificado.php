<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Exception;

class Certificado extends Model
{
    use HasFactory;

    protected $table = 'certificados';
    protected $primaryKey = 'id_certificado';

    protected $fillable = [
        'id_usuario',
        'id_curso',
        'codigo_autenticacao',
        'emitido_em',
    ];

    protected $casts = [
        'emitido_em' => 'datetime',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }

    /**
     * Valida se o colaborador cumpriu os requisitos para emissão do certificado.
     */
    public static function validarEmissao(int $idUsuario, int $idCurso): bool
    {
        $curso = Curso::find($idCurso);

        if (!$curso) {
            throw new Exception('O curso solicitado não foi encontrado.');
        }

        $jaEmitido = static::where('id_usuario', $idUsuario)
            ->where('id_curso', $idCurso)
            ->exists();

        if ($jaEmitido) {
            throw new Exception('Você já possui um certificado emitido para este curso.');
        }

        $progresso = Progresso::where('usuario_id', $idUsuario)
            ->where('curso_id', $idCurso)
            ->first();

        if (!$progresso || $progresso->porcentagem < 100) {
            throw new Exception('Você precisa concluir 100% do curso para solicitar o certificado.');
        }

        return true;
    }
}
