<?php

namespace App\Http\Controllers;

use App\Models\Certificado;
use App\Models\Curso;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use App\Jobs\GerarPdfCertificadoJob;

class CertificadoController extends Controller
{
    public function baixarCertificado(Request $request, $idCurso)
    {
        $usuario = auth()->user(); 

        if (!$usuario) {
            return response()->json(['message' => 'Não autorizado.'], 401);
        }

        try {
            // 1. Valida a regra de negócio (100% de conclusão)
            Certificado::validarEmissao($usuario->id_usuario, $idCurso);
            
            $curso = Curso::findOrFail($idCurso);

            // 2. Registra no banco de dados que este certificado foi emitido (se já não existir)
            $registro = Certificado::firstOrCreate(
                [
                    'id_usuario' => $usuario->id_usuario,
                    'id_curso' => $idCurso,
                ],
                [
                    // Gera um código único e bonito (ex: GEAR-XXXXXX) para validação futura
                    'codigo_autenticacao' => 'GEAR-' . strtoupper(Str::random(10)),
                    'emitido_em' => now(),
                ]
            );

            // 3. Renderiza a view do PDF passando o registro com o código de validação
            $pdf = Pdf::loadView('certificados.template', compact('usuario', 'curso', 'registro'));

            return $pdf->download("certificado-{$curso->nome}.pdf");

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 403);
        }
    }
    public function solicitar(Request $request, $idCurso)
    {
        $aluno = $request->user();
        
        // Valida se pode emitir
        Certificado::validarEmissao($aluno->id_usuario ?? $aluno->id, $idCurso, $aluno);

        // Dispara a fila
        GerarPdfCertificadoJob::dispatch($aluno->id_usuario ?? $aluno->id, $idCurso);

        return response()->json([
            'message' => 'Sua solicitação de certificado foi recebida e está sendo processada.'
        ], 202);
    }
}