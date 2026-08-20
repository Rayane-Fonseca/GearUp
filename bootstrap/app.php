<?php

use App\Http\Middleware\PerfilMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'perfil' => PerfilMiddleware::class,
        ]);

        // O Render roda a aplicação atrás de um proxy reverso.
        // Sem isso, o Symfony/Laravel não sabe confiar nos headers
        // X-Forwarded-* e pode lançar "Invalid URI: Host is malformed".
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();