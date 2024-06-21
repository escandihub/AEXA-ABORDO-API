<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\API\Usuario;
use App\Models\Diario;
use App\Http\Resources\CorridaResource;
use App\Http\Resources\PasajeroResource;
use App\Models\API\Pasajero;
use Illuminate\Support\Facades\Log;
use \Carbon\Carbon;

class CorridasController extends Controller
{
    /**
     * De acuerdo al tipo de usuario se indexara
     * las corridas correspondidas
     */
    public function index(Request $request)
    {

        $usuario = Usuario::find($request->user()->id_usuario);
        $taquilla = $usuario->taquilla;

        $fecha = '2024-05-25'; #\Carbon\Carbon::now()->format('Y-m-d')
        $inicioH = Carbon::now()->subHour(2);
        $finH = Carbon::now()->addHour(1);
        $corridas = Diario::where('destino', $taquilla->abreviacion)
            // ->whereBetween('hora', [$inicioH->hour, $finH->hour]) 
            ->where('fecha', $fecha)
            ->orderBy('id_diario_c', 'desc')->get();

        Log::debug($corridas->count());

        return  CorridaResource::collection($corridas)->resolve();
    }

    /**
     * Se optine los datos de una corrida 
     * solo la que el usuario este autorizado
     */
    public function show($id) {
        $diario = Diario::find($id);

        $v = $diario->pasajero()->where('abordo', 0)->get();

        // $pasajero = Pasajero::Where('id_diario_c', $id)->where('abordo', 0)->get();
        
        return response()->json([
            "diario_id" => $diario->id_diario_c,
            "car_code" => $diario->autobus,
            "date" => "{$diario->hora}:{$diario->minutos}",
            "hour" => $diario->fecha,
            "passengers" => $diario->capacidad,
            "disponibilidad" => $diario->disponibles,
            "route" => [
                "from" => $diario->origen,
                "to" => $diario->destino,
            ],
            "pasajeros" => PasajeroResource::collection($v)->resolve()

        ], 200);
        return  PasajeroResource::collection($v)->resolve();
    }

    /**
     * agregar middleware por si no existe el id del usuario
     */
    public function update($id){
        $pasajero = Pasajero::find($id);

        $pasajero->update(['abordo' => 1]);

        return response()->json(["messaje" => "OK" ], 200);
    }
}
