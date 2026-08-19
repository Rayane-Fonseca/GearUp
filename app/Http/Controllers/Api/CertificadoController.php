<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificadoController extends Controller
{
    /**
     * Gera e força o download do PDF do certificado se o curso estiver 100% concluído.
     */
    public function emitir(Request $request, $id_curso)
    {
        $usuario = $request->user();
        $curso = Curso::where('status', 'ativo')->findOrFail($id_curso);

        // 1. Validar se o aluno de fato completou 100% do curso
        $progresso = $usuario->progressoNoCurso($id_curso);

        if ($progresso < 100) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Você precisa concluir 100% das aulas para emitir o certificado. Progresso atual: ' . $progresso . '%'
            ], 403);
        }

        // 2. Montar dados que serão enviados para a View Blade do certificado
        $dados = [
            'nome_aluno' => $usuario->nome,
            'nome_curso' => $curso->titulo,
            'carga_horaria' => $curso->carga_horaria,
            'data_conclusao' => now()->translatedFormat('d \d\e F \d\e Y'), // Ex: 16 de Julho de 2026
            'codigo_autenticidade' => 'GU-' . strtoupper(uniqid()) . '-' . date('Y')
        ];

        // 3. Renderizar a view e carregar no Dompdf com orientação Paisagem (Landscape)
        $pdf = Pdf::loadView('certificados.modelo_padrao', $dados)
            ->setPaper('a4', 'landscape');

        // 4. Retornar o download do arquivo PDF
        $filename = 'Certificado_' . str_replace(' ', '_', $curso->titulo) . '.pdf';
        
        return $pdf->download($filename);
    }
}