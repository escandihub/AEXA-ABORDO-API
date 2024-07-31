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
use Illuminate\Support\Facades\DB;
use App\Models\DiarioCiudad;
use Illuminate\Database\Query\JoinClause;

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

        //'2024-07-31'; #
        $fecha = \Carbon\Carbon::now()->format('Y-m-d');
        $inicioH = Carbon::now()->subHour(1);
        $finH = Carbon::now()->addHour(1);

        /**
         * Se hace una subconsulta para acceder al hora:minutos
         */
        // se filtra por el dia y los hora 
        $corridas = DB::table('diario_c')->selectRaw("concat(hora,':', minutos) as hora, id_diario_c, llave_corrida")
        ->where('fecha', $fecha)
        // ->where('origen', $request->user()->taquilla->abreviacion)
        ->whereBetween('hora', [$inicioH->hour, $finH->hour]);
        
        $terminal = $request->user()->empleado->terminal;

        \Log::info($terminal->abreviacion);
        \Log::info($inicioH->hour);
        \Log::info($finH->hour);

        
        $ciudad = DB::table('diario_c')->select('diario_c.*')
        ->join('diario_c_ciudades', function(JoinClause $join)use($fecha, $inicioH, $finH, $terminal){
            $join->on('diario_c_ciudades.id_diario_c', '=', 'diario_c.id_diario_c')
            ->where('diario_c_ciudades.fecha', $fecha)
            ->whereBetween('diario_c_ciudades.hora', [$inicioH->hour, $finH->hour])
            ->where("diario_c_ciudades.{$terminal->abreviacion}", 1)
            ->where("diario_c_ciudades.destino","!=",$terminal->abreviacion);
        })->orderBy('diario_c_ciudades.hora', 'asc')->get();


        // ->orderBy('diario_c_ciudades.hora', 'desc')->get();

        // $corrida = DB::table('diario_c')->select("*")->joinSub($ciudad, 'ciudad', function($join)use($fecha){
        //     $join->on("ciudad.id_diario_c", '=', 'diario_c.id_diario_c');
        // })->orderBy('ciudad.hora', 'desc')->get();

        // luego se hace el join con los resultados para filtrar completamente
        // $corrida_ciudad = DiarioCiudad::selectRaw("*")->joinSub($corridas, 'corridas', function($join)use($inicioH, $finH, $terminal, $fecha){
        //     $join->on("corridas.llave_corrida", '=', 'diario_c_ciudades.llave_corrida');
        //     $join->where('fecha', $fecha);
        //     $join->where($terminal->abreviacion, 1);
        //     $join->whereBetween('corridas.hora', ["{$inicioH->hour}:$inicioH->minute", "{$finH->hour}:$finH->minute"]);
        //     $join->orderBy('corrida.hora', 'desc');
        // })->where('fecha', $fecha)->get();





        // $corridaFilter = Diario::selectRaw("*")->joinSub($corridas, 'corridas', function($join)use($inicioH, $finH){
        //     $join->on("corridas.id_diario_c", '=', 'diario_c.id_diario_c');
        //     $join->whereBetween('corridas.hora', ["{$inicioH->hour}:$inicioH->minute", "{$finH->hour}:$finH->minute"]);
        //     $join->orderBy('corrida.hora', 'desc');
        // })->get();

        Log::debug($corridas->count());

        return  CorridaResource::collection($ciudad)->resolve();
    }

    /**
     * Se optine los datos de una corrida 
     * solo la que el usuario este autorizado
     */
    public function show($id,  Request $request) {
        $diario = Diario::find($id);

        $v = $diario->pasajero()->where('abordo', 0)->get();

        // $pasajero = Pasajero::Where('id_diario_c', $id)->where('abordo', 0)->get();
        $user_terminal = $request->user()->empleado;
        $abordo = $this->a_abordar($v, $user_terminal);
        
        return response()->json([
            "diario_id" => $diario->id_diario_c,
            "car_code" => $diario->autobus,
            "date" => "{$diario->hora}:{$diario->minutos}",
            "hour" => $diario->fecha,
            "passengers" => $diario->capacidad,
            "disponibilidad" => $diario->disponibles,
            "a_abordar" => $abordo->count(),
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

        $diario = $pasajero->diario;
        $pasajero->diario()->update(["abordaron" => $diario->abordaron + 1 ]);
        $pasajero->update(["abordo" => 1]);
        return response()->json(["messaje" => "OK" ], 200);
    }

    public function a_abordar($pasajeros, $empleado){
        return $pasajeros->filter(function($pasajero)use($empleado){
            return $pasajero->numero_terminal === $empleado->numero_terminal;
        });
    }
}
