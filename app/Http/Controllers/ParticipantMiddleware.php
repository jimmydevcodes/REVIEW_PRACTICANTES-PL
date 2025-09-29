<?php

namespace App\Http\Middleware;

use App\Models\Participante;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsParticipant
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Verificar que el usuario autenticado existe como participante
        $participante = Participante::where('correo', Auth::user()->email)->first();

        if (!$participante) {
            return redirect()->route('dashboard')->with('error', 
                'Tu cuenta no está registrada como participante. Contacta al administrador.');
        }

        // Agregar el participante al request para uso posterior
        $request->merge(['participante' => $participante]);

        return $next($request);
    }
}

// Registrar el middleware en app/Http/Kernel.php
// Agregar esta línea en $routeMiddleware:
// 'participant' => \App\Http\Middleware\EnsureUserIsParticipant::class,