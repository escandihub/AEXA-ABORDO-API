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
use stdClass;

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
        $fecha_ =\Carbon\CarbonImmutable::now(); # \Carbon\CarbonImmutable::now(); #\Carbon\Carbon::parse('2024-08-19 23:30'); #\Carbon\Carbon::now()->format('Y-m-d');
        //$fecha_2 =\Carbon\CarbonImmutable::parse('2024-08-26 23:00');  #\Carbon\CarbonImmutable::now(); #\Carbon\Carbon::parse('2024-08-19 23:30'); #\Carbon\Carbon::now()->format('Y-m-d');
        // \Log::info($fecha);
        $fecha_2 = $fecha_->copy();
        $inicioH = $fecha_->copy()->subHour(2); #Carbon::parse(""); //Carbon::now()->subHour(1);
        $finH = $fecha_2 ;# Carbon::now()->addHour(1);
        $fecha = $fecha_->copy()->subHour(1)->format('Y-m-d H:i');
        $fecha2 = $fecha_2->copy()->addHour(2); #->format('Y-m-d H:i');
        // \Log::info('---');
        //  \Log::info($fecha2->format("H"));
        // \Log::info($fecha_2->copy()->format('Y-m-d'));

        // SI EL HORARIO ACTUAL ES DE MADRUGADA REALIZAR UNA CONSULTA EL DIA ANTERIOR A LAS 23 HORAS
        if($fecha_->format('H') >= 1 &&  $fecha_->format('H') <= 5){
            $fecha_ = $fecha_->subDay(1)->setTime(21, 30, 00);
        }

        /**
         * Se hace una subconsulta para acceder al hora:minutos
         */
        // se filtra por el dia y los hora 
        // $corridas = DB::table('diario_c')->selectRaw("concat(hora,':', minutos) as hora, id_diario_c, llave_corrida")
        // ->where('fecha', $fecha)
        // ->where('origen', $request->user()->taquilla->abreviacion)
        // ->whereBetween('hora', [$inicioH->hour, $finH->hour]);
        
        $terminal = $request->user()->empleado->terminal;

        // \Log::info($terminal->abreviacion);
        // \Log::info($fecha);
        // \Log::info($fecha2);
        // \Log::info($inicioH->hour);
        // \Log::info($finH->hour);

  /*      
        $ciudad = DB::table('diario_c')->select('diario_c.*')
        ->join('diario_c_ciudades', function(JoinClause $join)use($fecha, $fecha2, $inicioH, $finH, $terminal){
            $join->on('diario_c_ciudades.id_diario_c', '=', 'diario_c.id_diario_c')
            ->whereBetween('diario_c_ciudades.fecha', [$fecha, $fecha2])
            ->whereBetween('diario_c_ciudades.hora', [$inicioH->hour, $finH->hour])
            ->where("diario_c_ciudades.{$terminal->abreviacion}", 1)
            ->where("diario_c_ciudades.destino","!=",$terminal->abreviacion);
        })->orderBy('diario_c_ciudades.hora', 'asc')->get();
*/
        $ciudad = null;
        if ($fecha_->format('H') >= 23 || $fecha_->format('H') <= 2) {
            $corida_hora = DB::table('diario_c')->select('id_diario_c')->whereRaw('(fecha = ? and hora >= ?) or (fecha = ? and hora <= ?)', [$fecha_->format("Y-m-d"), $fecha_->format("H"), $fecha2->format("Y-m-d"), $fecha2->format("H")])->get();
            $ciudad = DB::table('diario_c')->select("diario_c.*")
            ->whereIn('id_diario_c', $corida_hora->pluck('id_diario_c'))
            ->where('origen', $terminal->abreviacion)
            ->where('condicion_corrida','=', 'Disponible')
            ->orderBy('hora', 'asc')
            ->get();
        }else {
            $ciudad = DB::table('diario_c')->select("diario_c.*")
            ->whereRaw('STR_TO_DATE(CONCAT(fecha, " ", hora), "%Y-%m-%d %H:%i") between ? AND ?', [$fecha, $fecha2])
            ->where('origen', $terminal->abreviacion)
            ->where('condicion_corrida','=', 'Disponible')
            ->orderBy('hora', 'asc')
            ->get();
    
        }
       
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

        // Log::debug($corridas->count());

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
    public $madrugada = false;
    public function readCorridas($request = null){
    
        $this->madrugada = false;

        $hoy = \Carbon\CarbonImmutable::now();  # \Carbon\CarbonImmutable::now(); #\Carbon\CarbonImmutable::parse('2024-08-20 01:00'); 
        // $fecha2 = \Carbon\Carbon::parse('2024-08-19 23:30')->addHour(2);
        $fecha2 = $hoy->copy()->addHour(3);

        // $fecha = $hoy->format('Y-m-d');
        $fecha = $hoy->subHour(6);
        // SI EL HORARIO ACTUAL ES DE MADRUGADA REALIZAR UNA CONSULTA EL DIA ANTERIOR A LAS 23 HORAS
        if($hoy->format('H') >= 00 &&  $hoy->format('H') <= 3){
            \Log::info('es de madrugada?');
            $this->madrugada = true; 
            $fecha = $hoy->subDay(1)->setTime(21, 30, 00);
        }

        $columnas = [];
        $result = '';
        $terminal_user =  $request->user()->empleado->nombre_taquilla; //$request->user()->empleado->nombre_taquilla; #terminal->abreviacion;  #nombre_taquilla;
        // \Log::info("fechas --- inter");
        //  \Log::info($fecha);
        //  \Log::info($fecha2);
        // \Log::info($terminal_user);


        $corridas = DB::table('diario_c')->select('*') //->where('fecha', '=', $fecha)
        ->whereRaw('STR_TO_DATE(CONCAT(fecha," ", hora,":",minutos), "%Y-%m-%d %H:%i") between ? AND ?', [$fecha->format('Y-m-d H:i'), $fecha2->format('Y-m-d H:i')])
        ->where('condicion_corrida','=', 'Disponible')
        // ->orderBy('hora', 'asc')
        ->get(); 
/*
        if ($hoy->format('H') >= 23 || $hoy->format('H') <= 2) {
        $corida_hora = DB::table('diario_c')->select('id_diario_c')->whereRaw('(fecha = ? and hora >= ?) or (fecha = ? and hora <= ?)', [$fecha->format("Y-m-d"), $fecha->format("H"), $fecha2->format("Y-m-d"), $fecha2->format("H")])->get();
           
        $corridas = DB::table('diario_c')->select('*') //->where('fecha', '=', $fecha)
        ->whereIn('id_diario_c', $corida_hora->pluck('id_diario_c'))
        // ->whereRaw('STR_TO_DATE(CONCAT(fecha, " ", hora), "%Y-%m-%d %H:%i") between ? AND ?', [$fecha, $fecha2])
        
        ->where('condicion_corrida','=', 'Disponible')
        // ->orderBy('hora', 'asc')
        ->get(); 
        }else{
            $corridas = DB::table('diario_c')->select('*') //->where('fecha', '=', $fecha)
        ->whereRaw('STR_TO_DATE(CONCAT(fecha), "%Y-%m-%d %H:%i") between ? AND ?', [$fecha->format('Y-m-d'), $fecha2->format('Y-m-d')])
        ->where('condicion_corrida','=', 'Disponible')
        // ->orderBy('hora', 'asc')
        ->get(); 
        } */
        //  \Log::info($corridas);
        /**
         * Se hace el recorido de las corridas para obtener las columnas correspondientes
         * al usuario actual autenticado mediante un procedimiento almacenado.
        //  */
        // \Log::info("--------");
        // \Log::info("inicia: {$fecha->format('Y-m-d H:i')} - {$fecha2->format('Y-m-d H:i')} ") ;
        //  \Log::info('primer select');
        //  \Log::info($corridas);
        //  \Log::info("--------");
        foreach ($corridas as $key => $corrida) {                                                  # TERMINAL  quite esa frase
            $result =   DB::select("call getColumn(?,?,@val)", [$corrida->id_diario_c, "{$terminal_user}"]);

            if($result[0] instanceof stdClass){
                // \Log::info(get_object_vars($result[0]));
                if(property_exists($result[0], 'terminal')){
                    $columnas[] = $result[0];
                }
            }
        }

        $corridas_ = collect($columnas);
        //\Log::info($columnas);
       # se crean las fechas 
        $inicioH = $fecha; #$fecha1->subHour(5); #Carbon::now()->subHour(4);
        $finH = $fecha2; #$fecha2->addHour(6); #Carbon::now()->addHour(1);
        \Log::info($inicioH);
        \Log::info($finH);
        $filtroDate = $this->lastFilter($corridas_, $hoy, $fecha2);
        $filtroDate->values();
        $diario = DB::table('diario_c')->selectRaw('diario_c.id_diario_c,diario_c.origen,diario_c.destino,diario_c.clase,diario_c.autobus,diario_c.capacidad,diario_c.disponibles,diario_c.fecha, diario_c.hora, diario_c.minutos')->whereIn('id_diario_c', $filtroDate->pluck('id_diario_c'))->orderBy("fecha", "desc")->get()
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
                "hora" =>  "{$terminal->values()->pluck('hora')->first()}:{$terminal->values()->pluck('minutos')->first()}",
                "datatime" => $corrida->fecha . " {$terminal->values()->pluck('hora')->first()}:{$terminal->values()->pluck('minutos')->first()}",
            ];
        });

        $diario = $diario->values();
        return $diario;
        return  CorridaResource::collection($diario, 1)->resolve();
        // return  CorridaResource::collection($diario, 1)->resolve();
        // return  new CorridaResource($diario, 1);
    }

    public function getNumberRow($column = 'terminal8'){
        return substr(strrchr($column, 'l'), 1);;
    }

    function choseTyeOfquery(Request $request){
        $usuario = $request->user()->empleado->terminal->abreviacion;

        // \Log::info("-------NOM TAQUILLA");
        // \Log::info($usuario->nombre_taquilla);
        if($usuario == "TGZ" || $usuario == 'TAP'){
            return $this->index($request);
        }else{
            return $this->readCorridas($request);
        }
    }

    /**
     * Filtro de la collection de Laravel
     * Solo mostrara el rango establecido y los estadus de cada corrida => cuando ya es de madrugada.
     */
    public function lastFilter($corridas_, $fecha, $fecha2) {
       return $corridas_->filter(function($c)use($fecha, $fecha2) {
            $date_corrida = Carbon::parse("{$c->fecha} {$c->hora}:{$c->minutos}");
            #agregar filtro despues de hacer pruebas prod
            // && $c->status != 'S'
            // \Log::info($date_corrida);
             return $this->comparative($date_corrida, $fecha, $fecha2)  &&  $c->status != 'C' && $c->status != 'F' &&  $c->status != 'S' ;
            // return  $date_corrida->between($fecha, $fecha2)  &&  $c->status != 'C' && $c->status != 'F';
            // if($this->madrugada){
            //     return  $c->status != 'C' && $c->status != 'F';
            // }else{
            //     return  $date_corrida->between($fecha, $fecha2)  &&  $c->status != 'C' && $c->status != 'F';
            // }
        });
    }

    public function comparative($date, $fecha1, $fecha2){
        // $currentTime = new \DateTime($date->format('Y-m-d H:i'));
        // $startTime = new \DateTime($fecha1->format('Y-m-d H:i'));
        // $endTime = new \DateTime($fecha2->format('Y-m-d H:i'));
        $currentTime = $date;
        $startTime = $fecha1->copy()->subMinutes(30);
        $endTime =  $fecha1->copy()->addMinutes(30);
        //\Log::info('hoy: ' . $currentTime->format('Y-m-d H:i') . ">=" . 'start: ' .  $startTime->format('Y-m-d H:i') . "<=" . "end: " . $endTime->format('Y-m-d H:i'));
        // \Log::info('- comparativa -');
        // \Log::info($currentTime->format('H:i') . ">=" . $startTime->format('H:i') . "<=" . $endTime->format('H:i'));
        if($this->madrugada){
            \Log::info('hoy: ' . $currentTime->format('Y-m-d H:i') . ">=" . 'start: ' .  $fecha2->format('Y-m-d H:i') . ' || ' . 'hoy: ' . $currentTime->format('Y-m-d H:i') . "<=" . "end: " . $endTime->format('Y-m-d H:i'));
        
           // $currentTime = $date->format("Y-m-d  H:i");
           // $startTime = $fecha2->format("Y-m-d H:i");
            //$endTime =  $fecha1->format("Y-m-d H:i");
            // if ($currentTime->between($fecha2, $endTime)) {
            //\Log::info('hoy: ' . $currentTime->format('Y-m-d H:i') . ">=" . 'start: ' .  $startTime->format('Y-m-d H:i') . "<=" . "end: " . $endTime->format('Y-m-d H:i'));
            //if ($fecha2->format("H") >= $currentTime->format("H") && $currentTime->format("H") <= $endTime->format("H")) { // forma que ya queda solo mustra unas mas pero solo en madrugada
            $currentTime = $this->convertDate($date, $endTime);
            if ($currentTime->between($startTime, $endTime)) {
                return true;
            }
        }else{
            if ($currentTime->between($startTime, $endTime)) {
            // if ($currentTime >= $startTime  && $currentTime <= $endTime) {
                return true;
            }
        }
        return false;
    }

    function convertDate($date, $end){
        //\Log::info('---- : ' .  $date->format('Y-m-d H:i'));
        if ($date->format("H") >= 0 && $date->format("H") <= 3 && $date->format("d") != $end->format("d")) {
            return $date->addDay(1);
        }
        return $date;
    }
}
