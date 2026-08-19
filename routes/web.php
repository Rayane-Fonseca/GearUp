<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\Api\ProgressoController;
use App\Http\Controllers\AulaController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas / Convidados (Guest)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
});

/*
|--------------------------------------------------------------------------
| Redirecionamentos Iniciais
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->isAdministrador()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('aluno.inicio');
    }
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return Auth::user()->isAdministrador()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('aluno.inicio');
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Rotas Autenticadas
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // --- Perfil de Usuário ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');

    // --- Módulo Colaborador / Aluno ---
    Route::prefix('aluno')->name('aluno.')->middleware('perfil:colaborador')->group(function () {
        Route::get('/inicio', [AlunoController::class, 'inicio'])->name('inicio');
        Route::get('/cursos', [AlunoController::class, 'cursos'])->name('cursos');
        Route::get('/trilhas', [AlunoController::class, 'trilhas'])->name('trilhas');
        Route::get('/trilhas/{id}', [AlunoController::class, 'trilhaDetalhe'])->name('trilha-detalhe');
        
        // Certificados
        Route::get('/certificados', [AlunoController::class, 'certificados'])->name('certificados');
        Route::post('/certificados/{idCurso}/solicitar', [CertificadoController::class, 'solicitar'])->name('certificados.solicitar');
        Route::get('/certificados/{id}/download', [CertificadoController::class, 'download'])->name('certificados.download');
        Route::get('/certificados/{id}/preview', [CertificadoController::class, 'preview'])->name('certificados.preview');

        // Perfil e Notificações
        Route::get('/perfil', [AlunoController::class, 'perfil'])->name('perfil');
        Route::get('/notificacoes', [AlunoController::class, 'notificacoes'])->name('notificacoes');

        // Visualização de Cursos e Player de Aulas
        Route::get('/cursos/{curso}', [CursoController::class, 'show'])->name('cursos.show');
        Route::get('/cursos/{curso}/aulas/{aula}', [AulaController::class, 'show'])->name('aulas.show');
    });

    // --- Módulo Administrador ---
    Route::prefix('admin')->name('admin.')->middleware('perfil:administrador')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/perfil', [AdminController::class, 'index'])->name('perfil');

        // Gestão de Cursos
        Route::get('/cursos', [AdminController::class, 'cursos'])->name('cursos');
        Route::post('/cursos', [AdminController::class, 'cursosStore'])->name('cursos.store');
        Route::get('/cursos/{curso}/gerenciar', [AdminController::class, 'cursosGerenciar'])->name('cursos.gerenciar');
        Route::put('/cursos/{curso}', [AdminController::class, 'cursosUpdate'])->name('cursos.update');
        Route::delete('/cursos/{curso}', [AdminController::class, 'cursosDestroy'])->name('cursos.destroy');

        // Gestão de Módulos
        Route::post('/cursos/{curso}/modulos', [AdminController::class, 'moduloStore'])->name('modulos.store');
        Route::put('/modulos/{modulo}', [AdminController::class, 'moduloUpdate'])->name('modulos.update');
        Route::delete('/modulos/{modulo}', [AdminController::class, 'moduloDestroy'])->name('modulos.destroy');

        // Gestão de Aulas
        Route::post('/modulos/{modulo}/aulas', [AdminController::class, 'aulaStore'])->name('aulas.store');
        Route::put('/aulas/{aula}', [AdminController::class, 'aulaUpdate'])->name('aulas.update');
        Route::delete('/aulas/{aula}', [AdminController::class, 'aulaDestroy'])->name('aulas.destroy');

        // Gestão de Colaboradores
        Route::get('/colaboradores', [AdminController::class, 'colaboradores'])->name('colaboradores');
        Route::post('/colaboradores', [AdminController::class, 'colaboradoresStore'])->name('colaboradores.store');
    });

    // --- Atualização de Progresso do Aluno ---
    Route::post('/progresso/atualizar', [ProgressoController::class, 'atualizarProgresso'])->name('progresso.atualizar');
});

require __DIR__ . '/auth.php';