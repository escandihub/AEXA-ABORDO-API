<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\helpers\formula;
use App\Models\API\Terminal;
use App\Models\API\Taquilla;

class isLocationValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $coordenates = $request->coordenadas;

        $taquilla = Taquilla::where('taquilla', $request->user->id_empleado)->first();
        $terminal = Terminal::where('abreviacion', $taquilla->abreviacion)->first();

        $calculo = new formula();
        $isValid = $calculo->isPointWithinRadius($terminal->latitud, $terminal->longitud, $request->lat, $request->log, $terminal->radio);

        if ($isValid) {
            return $next($request);
        }
        return response()->json(["error" => "No se encuentra dentro de la Terminal."], 422);
    }
}
