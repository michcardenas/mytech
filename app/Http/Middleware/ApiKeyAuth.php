<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyAuth
{
    /**
     * Autentica peticiones a la API de proyectos mediante una API key estática.
     * Acepta el token en "Authorization: Bearer <key>" o en el header "X-API-Key".
     */
    public function handle(Request $request, Closure $next): Response
    {
        $expected = config('proyectos.api_key');
        $provided = $request->bearerToken() ?: $request->header('X-API-Key');

        if (empty($expected) || empty($provided) || ! hash_equals($expected, $provided)) {
            return response()->json([
                'message' => 'No autorizado. Falta o es inválida la API key.',
            ], 401);
        }

        return $next($request);
    }
}
