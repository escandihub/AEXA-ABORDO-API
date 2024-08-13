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
use Illuminate\Support\Facades\Schema;

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

        return  CorridaResource::collection($ciudad, 1)->resolve();
    }

    /**
     * Se optine los datos de una corrida 
     * solo la que el usuario este autorizado
     */
    public function show($id,  Request $request) {
        $diario = Diario::find($id);

        $v = $diario->pasajero()->where('abordo', 0)->where("status", "V")->get();

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
    public function update(Request $request, $id){
        $diario =  $request->diario_id;

        $result =   DB::select("call updatePasajero(?, ?, @val)", [$id, $diario]);

        if ($result[0]->valido) {
            return response()->json(["messaje" => "OK" ], 200);
        }
        return response()->json(["messaje" => "Ticket invalido" ], 400);

        // $pasajero = Pasajero::find($id);
        // if(!$pasajero->abordo){
        //     try {
        //         $result =   DB::select("call updatePasajero(?, ?, @val)", [$id, $diario]);
        //     } catch (\Throwable $th) {
        //         \Log::info($th->getMessage());
        //     }
        // return response()->json(["messaje" => "OK" ], 200);
        // }
        // return response()->json(["messaje" => "Ticket invalido" ], 400);
    }

    public function a_abordar($pasajeros, $empleado){
        return $pasajeros->filter(function($pasajero)use($empleado){
            return $pasajero->numero_terminal === $empleado->numero_terminal;
        });
    }

    /**
     * Se obtiene la lista de terminales que pasara el autobus
     */
    public function terminales(){
        // $terminales = ["TGZ", "CIN", "CER", "ARR","TON", "PIJ","MAP","ESC", "HUI", "TAP", "SNC", "OCO", "PAL", "CIR","MAD","PQR","AGU","PTE","TGR","ORT","TIL","LIB","CND","CHN","DME","VAL","SNM","ECE","LCR","JIQ","LAF","OCZ","TGP","PTO","PTI","PTR","TPR","PR5","HHE"];
        // $condiciones = [];
        // foreach($terminales as $terminal){
        //     $condiciones[] = ["$terminal", ">", "0"];
        // }
        $fecha = "2024-07-30"; # \Carbon\Carbon::now()->format('Y-m-d');
        $inicioH = Carbon::now()->subHour(1);
        $finH = Carbon::now()->addHour(1);

        $usuario = "ARR";
        
        $corridas = DB::table('diario_c_ciudades')->where('id_diario_c', 427109)
        // ->where('diario_c_ciudades.fecha', $fecha)
        // ->whereBetween('diario_c_ciudades.hora', [$inicioH->hour, $finH->hour])
        ->get();
        $cols = [];
        $model = (new DiarioCiudad());
        $columnObjects = DB::select("SHOW COLUMNS FROM {$model->getTable()}");
        
        $columnNames = array_map(fn ($column) => $column->Field, $columnObjects);

        $trueColums = collect($columnNames)->filter(function ($column) use ($corridas){
            return $corridas->contains(function($corrida) use ($column){
                if(is_int($corrida->{$column})){
                    return $corrida->{$column} === 1;
                }
            });
        });

        return $trueColums;
    }

    public function renderCiudad($user = "ARR"){

        $fecha = "2024-07-30"; # \Carbon\Carbon::now()->format('Y-m-d');
        $inicioH =  "03:00"; # Carbon::now()->subHour(1);
        $finH = "05:00"; #Carbon::now()->addHour(1);
        $corridas = DB::table('diario_c')->select('diario_c.*')
        ->join('diario_c_terminales', function(JoinClause $join)use ($fecha, $inicioH, $finH){
            $join->on('diario_c_terminales.id_diario_c', "=", "diario_c.id_diario_c");
        });
    }

    public function isCity($corrida): Array{
        $model = (new DiarioCiudad());
        $columnObjects = DB::select("SHOW COLUMNS FROM {$model->getTable()}");
        
        $columnNames = array_map(fn ($column) => $column->Field, $columnObjects);

        return collect($columnNames)->filter(function ($column) use ($corrida){
            return $corrida->contains(function($corrida) use ($column){
                if(is_int($corrida->{$column})){
                    return $corrida->{$column} === 1;
                }
            });
        })->value->all();
    }

    public function getCorridas(Request $request){
        $user = "TON"; //$request->user()->empleado->terminal->abreviacion;
        $fecha =  \Carbon\Carbon::now()->format('Y-m-d');
        $inicioH =   Carbon::now()->subHour(4);
        $finH = Carbon::now()->addHour(1);

        \Log::info($inicioH->hour);
        \Log::info($finH->hour);

        $lista = DB::table('pasajeros')->selectRaw('distinct pasajeros.id_diario_c, pasajeros.hora as "pHora", pasajeros.minutos as "pMinutos"')->where('origen', '=', $user)
        ->whereNotIn('status', ['Z','C'])
        ->where('fecha_salida', '=', $fecha);

        $corridas = DB::table('diario_c')->select('diario_c.*', "filterCorrida.pHora", "filterCorrida.pMinutos")
        ->joinSub($lista, 'filterCorrida', function(JoinClause $join)use($fecha){
            $join->on("diario_c.id_diario_c", "=", "filterCorrida.id_diario_c");
        })->where('diario_c.fecha', $fecha)->get()->filter(function($corrida)use($inicioH, $finH){
            $date = Carbon::parse("{$corrida->pHora}:{$corrida->pMinutos}");
            return $date->between($inicioH, $finH);
        });

        return CorridaResource::collection($corridas, 2)->resolve();
    }

    #Request $request
    public function readCorridas($request){
    
        $fecha = \Carbon\Carbon::now()->format('Y-m-d');
        $columnas = [];
        $result = '';
        $terminal_user = $request->user()->empleado->terminal->abreviacion;
        $corridas = DB::table('diario_c')->select('*')->where('fecha', '=', $fecha)
        ->where('condicion_corrida','=', 'Disponible')
        ->get();

        //  $corridas->each(function($corrida)use($columnas){
        //  $result =   DB::select("call getColumn(?, @val)", [$corrida->id_diario_c]);
        // $columnas['current_column'] = $result[0]->terminal;
        // });
        // \Log::info($columnas);
        // return null;
        /**
         * Se hace el recorido de las corridas para obtener las columnas correspondientes
         * al usuario actual autenticado mediante un procedimiento almacenado.
         */
        $fecha = \Carbon\Carbon::now()->format('Y-m-d');
        foreach ($corridas as $key => $corrida) {
            $result =   DB::select("call getColumn(?,?,?,@val)", [$corrida->id_diario_c, "{$terminal_user} TERMINAL", $fecha]);
            $columnas[] = [
                "terminal" => $result[0]->terminal,
                "id" => $corrida->id_diario_c
            ];
        }

        /**
         * Una vez obtenida las columnas, se filtra de las cuales
         * el usuario autenticado no  pertenece y se obtiene el 
         * numero entero de la columna de la terminal.
         */
        $filter = collect($columnas)->filter(function($columna){
            return $columna['terminal'] != 'sin terminal';
        })->map(function($columna) {
            return [
                "id" => $columna['id'],
                "terminal" => $columna['terminal'],
                "col" => $this->getNumberRow($columna['terminal']),
            ];
        });

        $corridas_ = collect(); #se crea una colleccion 

        /**
         * Se hace una selecion individual para estandarizar las cabeceras de las terminales
         * y el resultado se agrega en la coleccion
         */
        foreach ($filter as $key => $corrida) {
            $value = DB::table('diario_c_terminales')->selectRaw("id_diario_c, terminal{$corrida['col']} AS terminal, status_t{$corrida['col']} AS status, hr{$corrida['col']} AS hora, min{$corrida['col']} AS minutos, fecha, CONCAT( hr{$corrida['col']},':',min{$corrida['col']}) AS horario")->where('id_diario_c', $corrida['id'])->first();
            $corridas_->push($value);
        }

        \Log::info($corridas_);
       # se crean las fechas 
        $fecha1 =  \Carbon\Carbon::now(); #\Carbon\Carbon::now()->format('Y-m-d');  \Carbon\Carbon::parse('2024-06-01 13:00'); #
        $fecha2 =  \Carbon\Carbon::now(); #\Carbon\Carbon::parse('2024-06-01 13:00'); # \Carbon\Carbon::now()->format('Y-m-d');
        // \Log::info($fecha);
        $inicioH = $fecha1->subHour(1); #Carbon::now()->subHour(4);
        $finH = $fecha2->addHour(6); #Carbon::now()->addHour(1);
        \Log::info($inicioH);
        \Log::info($finH);
        $filtroDate = $corridas_->filter(function($c)use($inicioH, $finH) {
            $date_corrida = Carbon::parse("{$c->fecha} {$c->horario}");

            // \Log::info($inicioH);
            // \Log::info($finH);
            // \Log::info($date_corrida->toString());
            // \Log::info();
            return   $date_corrida->between($inicioH, $finH) && $c->status != 'S' && $c->status != 'C' && $c->status != 'F';
        });

        // \Log::info('filtrado');
        // \Log::info($filtroDate);
        // \Log::info();
        $filtroDate->values();
        $diario = DB::table('diario_c')->selectRaw('diario_c.id_diario_c,diario_c.origen,diario_c.destino,diario_c.clase,diario_c.autobus,diario_c.capacidad,diario_c.disponibles,diario_c.fecha, diario_c.hora, diario_c.minutos')->whereIn('id_diario_c', $filtroDate->pluck('id_diario_c'))->get()
        ->map(function($corrida)use($filtroDate){
            $terminal = $filtroDate->filter(function($terminal)use($corrida){ return $terminal->id_diario_c == $corrida->id_diario_c; });
            return [
                "id" => $corrida->id_diario_c,
                "origen" => $corrida->origen,
                "destino" => $corrida->destino,
                "clase" => $corrida->clase,
                "bus" => [
                    "card_code" => $corrida->autobus,
                    "capacidad" => $corrida->capacidad,
                    "disponibilidad" => $corrida->disponibles,
                ],
                "fecha" => $corrida->fecha,
                "hora" =>  "{$terminal->values()->pluck('hora')->first()}:{$terminal->values()->pluck('minutos')->first()}"
            ];
        });
        \Log::info($diario);
        \Log::info('Detalles: type -----');

        #return 0;
        $diario = $diario->values();
        return $diario;
        return  CorridaResource::collection($diario, 1)->resolve();
        // return  CorridaResource::collection($diario, 1)->resolve();
        // return  new CorridaResource($diario, 1);
    }

    public function getNumberRow($column = 'terminal8'){
        return substr($column, -1);
    }

    function choseTyeOfquery(Request $request){
        $usuario = $request->user()->empleado->terminal;

        if($usuario->abreviacion == "TGZ"){
            return $this->index($request);
        }else{
            return $this->readCorridas($request);
        }
    }
}
