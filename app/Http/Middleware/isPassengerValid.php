<?php

namespace App\Http\Middleware;

use App\Models\API\Pasajero;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Log;

class isPassengerValid
{
    /**
     * Handle an incoming request.
     * 
     * verifica que la corrida sea parte el usuario que se va a escanear
     * para no procesar el pasajero
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validator = Validator::make($request->all(), [
            'diario_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()], 422);
        }


        $param = 0;
        if ($request->id) {
            $param = $request->id;
            if ($this->isPartOfCorrida($param, $request)) {
                return $next($request);
            }
        }
        return response()->json(["error" => "El pasajero no es de la corrida actual."], 422);
    }

    public function isPartOfCorrida($id, $request)
    {
        $pasajero = Pasajero::find($id);

        if ($pasajero->id_diario_c == $request->diario_id) {
            return true;
        } else {
            return false;
        }
    }
}
