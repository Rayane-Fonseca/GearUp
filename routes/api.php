<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CursoController;
use App\Http\Controllers\Api\TrilhaController; 
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProgressoController;
use App\Http\Controllers\CertificadoController;

Route::prefix('v1')->group(function () {

    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login_api');

    Route::middleware('auth:sanctum')->group(function () {
        
        // Autenticação & Geral
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/home', [ProgressoController::class, 'home']);
        Route::get('/dashboard', [DashboardController::class, 'index']);

        // Cursos
        Route::get('/cursos', [CursoController::class, 'index']);
        Route::get('/cursos/{id}', [CursoController::class, 'show']);
        Route::get('/cursos/{id_curso}/progresso', [ProgressoController::class, 'progressoCurso']);

        // Trilhas
        Route::get('/trilhas', [TrilhaController::class, 'index']);
        Route::get('/trilhas/{id}', [TrilhaController::class, 'show']);

        // Aulas e Progresso
        Route::post('/aulas/{id_aula}/toggle', [ProgressoController::class, 'toggleAula']);
        Route::post('/progresso/toggle', [ProgressoController::class, 'toggleAulaConcluida']);

        // Certificados
        Route::post('/cursos/{id_curso}/solicitar-certificado', [CertificadoController::class, 'solicitar']);
        
        // Rota para baixar o PDF usando o ID do Certificado (Compatível com o botão do Filament)
        Route::get('/certificados/{id_certificado}/download', [CertificadoController::class, 'baixarCertificado'])
            ->name('certificado.baixar');
    });
});