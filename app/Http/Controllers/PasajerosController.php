<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\API\Pasajero;
use App\Models\Diario;
use App\Http\Resources\PasajerosResource;

class PasajerosController extends Controller
{
    /**
     * el no aborda para que es 
     * cuando el pasajero va a subir en otra terminal
     */
    public function asientos(Request $request)
    {
        $diario = Diario::find($request->corrida);
        $pasajero = $diario->pasajero()->select("numero_asiento", "abordo")->get();
        // $pasajaero = Pasajero::where('id_diario_c', $request->corrida)
        // ->select("numero_asiento", "abordo")->get();

        return response()->json([
            "capacidad" =>  $diario->capacidad,
            "disponibilidad" =>  $diario->disponibilidad,
            "asientos" => PasajerosResource::collection($pasajero)->resolve()
         ], 200);
    }
}