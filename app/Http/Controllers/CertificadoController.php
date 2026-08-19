<?php

namespace App\Http\Controllers;

use App\Models\Certificado;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class CertificadoController extends Controller
{
    public function preview($id)
    {
        // Carregamos apenas 'usuario' e 'curso' (pois 'instrutor' já é uma coluna de 'curso')
        $certificado = Certificado::with(['usuario', 'curso'])
            ->where('id_certificado', $id)
            ->first();

        if (!$certificado) {
            dd("ERRO: O certificado com id_certificado = '{$id}' não existe no banco de dados.");
        }

        $pdf = Pdf::loadView('pdf.certificado', compact('certificado'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('certificado-' . $id . '.pdf');
    }

    public function download($id)
{
    $certificado = Certificado::with(['usuario', 'curso'])
        ->where('id_certificado', $id)
        ->first();

    if (!$certificado) {
        dd("ERRO: O certificado com id_certificado = '{$id}' não existe no banco de dados.");
    }

    // 1. Pega os nomes do curso e do aluno
    $nomeCurso = $certificado->curso->titulo ?? $certificado->nome_curso ?? 'Curso';
    $nomeAluno = $certificado->usuario->nome ?? $certificado->usuario->name ?? $certificado->nome_aluno ?? 'Aluno';

    // 2. Remove ou substitui as barras '/' e '\' por hífen para não quebrar a header HTTP
    $nomeCursoLimpo = str_replace(['/', '\\'], '-', $nomeCurso);
    $nomeAlunoLimpo = str_replace(['/', '\\'], '-', $nomeAluno);

    // 3. Monta o nome do arquivo sanitizado
    $nomeArquivo = "{$nomeAlunoLimpo} - {$nomeCursoLimpo} .pdf";

    // 4. Configura e gera o PDF
    $pdf = Pdf::loadView('pdf.certificado', compact('certificado'))
        ->setPaper('a4', 'landscape');

    return $pdf->download($nomeArquivo);
}
}