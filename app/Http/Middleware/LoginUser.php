<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LoginUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validator = Validator::make($request->all(), [
            'user' => 'string|required',
            'password' => 'required'
        ]);

        
        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()], 422);
        }

        if(\App\Models\API\Usuario::where('user', $request->user)->where('pass', $request->password)->first()){
            return $next($request);    
        }
        return response()->json(["error" => "Credenciales invalidas"], 422);
    }
}
