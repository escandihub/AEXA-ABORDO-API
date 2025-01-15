<?php

namespace App\Http\Controllers;

use App\Http\Resources\PasajeroDocumentacionCollection;
use Illuminate\Http\Request;
use App\Models\API\Pasajero;
use App\Models\Diario;
use App\Http\Resources\PasajerosResource;
use App\Http\Resources\Passanger;
use App\Models\Documentation;
use Illuminate\Support\Facades\DB;

class PasajerosController extends Controller
{
    /**
     * el no aborda para que es 
     * cuando el pasajero va a subir en otra terminal
     */
    public function asientos(Request $request)
    {
        $terminal_empleado = $request->user()->empleado;
        $diario = Diario::find($request->corrida);
        $pasajero = $diario->pasajero()->select("consecutivo_terminal","folio_empleado","nombre","numero_asiento", "abordo", "numero_terminal", "origen","destino")->where("status", "V")
        // ->where('destino', '!=', $terminal_empleado->terminal->abreviacion)
        ->orderBy('numero_asiento', 'ASC')->get();
        // ->where("numero_terminal", $terminal_empleado->numero_terminal )

        
        // $pasajaero = Pasajero::where('id_diario_c', $request->corrida)
        // ->select("numero_asiento", "abordo")->get();

        return response()->json([
            "capacidad" =>  $diario->capacidad,
            "disponibilidad" =>  $diario->disponibilidad,
            "asientos" => PasajerosResource::collection($pasajero)->resolve(),
            "terminal" => $terminal_empleado->numero_terminal,
            "origen" => $terminal_empleado->terminal->abreviacion,
            "destino_corrida" => $diario->destino,
            "ListAbordo" => $this->terminales($request->corrida)
         ], 200);
    }

    public function terminales($id_diario){
        
        return DB::table('pasajeros')->selectRaw("terminal, count(origen) as cantidad")->where("id_diario_c", $id_diario)->whereNotIn('status', ["Z", "C"])->groupBy("terminal")->get()
        ->map(function($terminal){
            return [
                "terminal" => "{$terminal->terminal}",
                "cantidad" => $terminal->cantidad
            ];
        });
        // ->having('terminal', '!=', "TGZ ONLINE")->get();
    }

    public function getInfo($pasajero_id){
        $pasajero = Pasajero::find($pasajero_id);
        $bus = DB::table('diario_c')->select('autobus')->where('id_diario_c', $pasajero->id_diario_c)->first();
        return  response()->json([
            "id" => $pasajero->id_pasajero,
            "id_diario" => $pasajero->id_diario_c,
            "folio" => $pasajero->consecutivo_terminal,
            "nombre" => $pasajero->nombre,
            "hora"  => "{$pasajero->hora}:{$pasajero->minutos}", 
            "ruta" => "{$pasajero->origen} - {$pasajero->destino}",
            "asiento" => $pasajero->numero_asiento,
            "bus" => $bus->autobus,
            "empresa" => $pasajero->empresa
        ]);
    }

    public function saveDocumentation(Request $request){


        \Log::info($request->all());

        $pasajero_id = $request->pasajero_id;
        
        DB::beginTransaction();
        try {
            foreach ($request->document as $key => $doc) {
                Documentation::create([
                    "pasajero_id" => $request->id,
                    "type_id" => $doc['type'],
                    "uuid" => $doc['uuid'],
                    "number_document" => 1
                ]);
            }             
            
            DB::commit();
            return response()->json([
                "status" => 200,
                "message" => "se ha guardado con exito"
            ], 200);
        } catch (\Throwable $th) {
            \Log::info($th->getMessage());
            DB::rollBack();
            //throw $th;
        }
        
    }

    function getDocumentation($pasajero_id) {
        $pasajero = Pasajero::find($pasajero_id);

        return response()->json([
            "pasajero" => new Passanger($pasajero),
            "documents" => PasajeroDocumentacionCollection::collection($pasajero->document)->resolve()
        ], 200);
    }
    
    function updateDocument($pasajero){
        $pasajero = Pasajero::find($pasajero->id);
    }
}