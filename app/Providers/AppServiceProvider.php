<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- Adicione esta linha

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Força HTTPS e a URL pública correta no ambiente do Render.
        // Sem isso, o Laravel às vezes detecta o host errado através do proxy
        // (dependendo da rede do visitante), gerando URLs de CSS/JS erradas.
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
            URL::forceRootUrl(config('app.url'));
        }
    }
}