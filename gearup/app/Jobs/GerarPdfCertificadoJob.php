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
use Barryvdh\DomPDF\Facade\Pdf; // Ou a biblioteca de PDF que estiver usando
use Illuminate\Support\Facades\Storage;

class GerarPdfCertificadoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Definimos propriedades públicas simples para evitar problemas de serialização pesada
    public $idUsuario;
    public $idCurso;

    /**
     * Passamos apenas os IDs para manter o Job leve na fila
     */
    public function __construct($idUsuario, $idCurso)
    {
        $this->idUsuario = $idUsuario;
        $this->idCurso = $idCurso;
    }

    /**
     * O processamento pesado acontece aqui dentro
     */
    public function handle(): void
    {
        $aluno = Usuario::find($this->idUsuario);
        $curso = Curso::find($this->idCurso);

        if (!$aluno || !$curso) {
            return;
        }

        // 1. Gera o código único de autenticação exigido pela constraint do banco
        $codigoAutenticacao = (string) Str::uuid();

        // 2. Registra o certificado no banco de dados
        $certificado = Certificado::create([
            'id_usuario' => $aluno->id_usuario ?? $aluno->id,
            'id_curso' => $curso->id_curso ?? $curso->id,
            'codigo_autenticacao' => $codigoAutenticacao,
        ]);

        // 3. Renderiza o HTML e transforma em PDF (Exemplo utilizando DomPDF)
        $pdf = Pdf::loadView('emails.certificados.modelo_padrao', [
            'aluno' => $aluno,
            'curso' => $curso,
            'certificado' => $certificado
        ]);

        // 4. Salva o PDF gerado de forma assíncrona no Storage local
        $nomeArquivo = "certificados/{$codigoAutenticacao}.pdf";
        Storage::disk('local')->put($nomeArquivo, $pdf->output());
    }
}