<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PerfilMiddleware
{
    public function handle(Request $request, Closure $next, string $perfil): Response
    {
        if (! Auth::check() || Auth::user()->perfil !== $perfil) {
            abort(403, 'Você não tem permissão para acessar esta área.');
        }

        return $next($request);
    }
}
