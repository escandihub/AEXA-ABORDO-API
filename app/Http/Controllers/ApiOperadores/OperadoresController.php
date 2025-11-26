<?php

namespace App\Http\Controllers\ApiOperadores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExternalOperador as ModelsExternalOperador;
use App\Http\Controllers\ApiOperadores\OperadoresService;
class OperadoresController extends Controller
{
    public function index(Request $request)
    {
        $service = app(OperadoresService::class);
        $aexaOperadores = $service->getOperadoresAexaTours();

        $query = ModelsExternalOperador::query();
        if ($request->has('marca')) {
            $query->where('Empresa', 'LIKE', "%{$request->marca}%");
        }

        if ($request->has('nombre')) {
            $query->where('Nombre', 'LIKE', "%{$request->nombre}%");
        }

        if ($request->has('todo') && $request->todo === 'true') {
            $operadores = $query->get();
            return response()->json($service->mergeCollections($aexaOperadores, $operadores));
        }

        // Paginado por defecto
        return response()->json($query->where("Estatus", "ALTA")
            ->orWhere("Estatus", "alta")->get());
    }
}
