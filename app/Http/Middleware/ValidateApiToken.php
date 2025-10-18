<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ApiToken;

class ValidateApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         $authHeader = $request->header('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['error' => 'Token no proporcionado'], 401);
        }

        $tokenValue = substr($authHeader, 7);
        $token = ApiToken::where('token', $tokenValue)->where('active', true)->first();

        if (!$token) {
            return response()->json(['error' => 'Token inválido o inactivo'], 403);
        }

        // Asociar el usuario autenticado al request
        $request->merge(['auth_user' => $token->user]);
        
        return $next($request);
    }
}
