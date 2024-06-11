<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\API\Usuario;
use App\Models\Diario;
use App\Http\Resources\CorridaResource;
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

        $fecha = '2024-07-31'; #\Carbon\Carbon::now()->format('Y-m-d')
        $inicioH = Carbon::now()->subHour(2);
        $finH = Carbon::now()->addHour(1);
        $corridas = Diario::where('destino', $taquilla->abreviacion)
            ->whereBetween('hora', [$inicioH->hour, $finH->hour]) 
            ->where('fecha', $fecha)
            ->orderBy('id_diario_c', 'desc')->get();

        Log::debug($corridas->count());

        return  CorridaResource::collection($corridas);
    }
}
