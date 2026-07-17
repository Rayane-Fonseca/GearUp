<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CursoController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TrilhaController; 
use App\Http\Controllers\CertificadoController;
use App\Jobs\GerarPdfCertificadoJob;
use App\Models\Certificado;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ProgressoController;// <-- Não esqueça de importar!

Route::prefix('v1')->group(function () {
    // Rota pública
    Route::post('/login', [AuthController::class, 'login']);

    // Rotas protegidas por Token Sanctum
    Route::middleware('auth:sanctum')->group(function () {
    // Rotas de Cursos
    Route::get('/cursos', [CursoController::class, 'index']);
    Route::get('/cursos/{id}', [CursoController::class, 'show']);
    // Rotas de Trilhas
    Route::get('/trilhas', [TrilhaController::class, 'index']);
    Route::get('/trilhas/{id}', [TrilhaController::class, 'show']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/aulas/{id_aula}/toggle', [ProgressoController::class, 'toggleAula']);
    Route::get('/cursos/{id_curso}/progresso', [ProgressoController::class, 'progressoCurso']);
    Route::post('/progresso/toggle', [ProgressoController::class, 'toggleAulaConcluida']);
    // Rota pública protegida por Rate Limiting (máximo 5 requisições por minuto por IP)
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login_api');
    // Rotas de Cursos e Progresso
    Route::get('/cursos', [CursoController::class, 'index']);
    Route::get('/cursos/{id}', [CursoController::class, 'show']);
    // Nova rota para a tela de Início (Dashboard do Aluno)
    Route::get('/home', [ProgressoController::class, 'home']);
    // Route::get('/cursos/{id_curso}/certificado', [CertificadoController::class, 'emitir']);
    Route::middleware('auth:sanctum')->get('/cursos/{id_curso}/certificado', [CertificadoController::class, 'baixarCertificado']);
    });
        Route::middleware(['auth:sanctum'])->group(function () {
        
        // Insira a rota aqui dentro do grupo protegido
        Route::post('/cursos/{id_curso}/solicitar-certificado', function (Request $request, $idCurso) {
            $aluno = $request->user();
            
            // 1. Executa a validação de 100% que criamos na suíte de testes
            Certificado::validarEmissao($aluno->id_usuario ?? $aluno->id, $idCurso, $aluno);

            // 2. Despacha para a fila em background
            GerarPdfCertificadoJob::dispatch($aluno->id_usuario ?? $aluno->id, $idCurso);

            return response()->json([
                'message' => 'Sua solicitação de certificado foi recebida e está sendo processada.'
            ], 202); 
        });
    });
    Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/cursos/{id_curso}/solicitar-certificado', [CertificadoController::class, 'solicitar']);
    });
});