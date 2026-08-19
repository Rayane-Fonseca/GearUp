<?php

namespace App\Jobs;

use App\Models\Usuario;
use App\Models\Curso;
use App\Models\Certificado;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class GerarPdfCertificadoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $idUsuario;
    public $idCurso;

    public function __construct($idUsuario, $idCurso)
    {
        $this->idUsuario = $idUsuario;
        $this->idCurso = $idCurso;
    }

    public function handle(): void
    {
        $usuario = Usuario::find($this->idUsuario);
        $curso = Curso::find($this->idCurso);

        if (!$usuario || !$curso) {
            return;
        }

        $codigoAutenticacao = strtoupper('GEAR-' . Str::random(10));

        Certificado::create([
            'id_usuario' => $usuario->id_usuario,
            'id_curso' => $curso->id_curso,
            'codigo_autenticacao' => $codigoAutenticacao,
            'emitido_em' => now(),
        ]);
    }
}
