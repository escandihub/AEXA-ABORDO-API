<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\API\Terminal;
use App\Models\API\Taquilla;
use App\helpers\formula;

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
            'password' => 'required',
            'lat' => 'required',
            'log' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()], 422);
        }

        $usuario = \App\Models\API\Usuario::where('user', $request->user)->where('pass', $request->password)->first();

        if ($usuario) {
            if ($this->validLocation($usuario->id_usuario, $request->lat, $request->log)) {
                return $next($request);
            } else {
                return response()->json(["error" => "No se encuentra dentro de la Terminal."], 401);
            }
        } else {
            return response()->json(["error" => "Credenciales invalidas, por favor de validar sus credenciales."], 401);
        }
    }

    /**
     *  se dibuja en metros
     * -- algoritmo en... 
     * Se valida que el usuario se encuentre dentro del radio 
     * permitido para acceder a la aplicacion
     */

    public function validLocation($id_empleado, $lat, $log)
    {


        $taquilla = Taquilla::where('taquilla', $id_empleado)->first();
        $terminal = Terminal::where('abreviacion', $taquilla->abreviacion)->first();

        $calculo = new formula();
        \Log::info($log);
        $radio = ($terminal->radio / 1000); // se divide el radio q se guardo en metros para convertirlo en kilometros
        $isValid = $calculo->isPointWithinRadius($terminal->latitud, $terminal->longitud, $lat, $log, $radio);

        if ($isValid) {
            return true;
        }
        return false;
        return response()->json(["error" => "No se encuentra dentro de la Terminal."], 401);
    }
}
