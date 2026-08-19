<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- Adicione esta linha

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Força HTTPS no ambiente do Render
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
