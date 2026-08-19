<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notificacao; // Ajuste para a sua Model de Notificação

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Compartilha a variável $notificacoes com o layout do aluno
        View::composer('components.aluno-layout', function ($view) {
            $notificacoes = Notificacao::latest()->take(5)->get(); // Ou a sua consulta
            $view->with('notificacoes', $notificacoes);
        });
    }
}
