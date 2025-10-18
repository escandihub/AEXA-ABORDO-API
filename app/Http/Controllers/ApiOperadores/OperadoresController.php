<?php

namespace App\Http\Controllers\ApiOperadores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExternalOperador as ModelsExternalOperador;

class OperadoresController extends Controller
{
    public function index(Request $request)
    {
        $query = ModelsExternalOperador::query();
        if ($request->has('marca')) {
            $query->where('Empresa', 'LIKE', "%{$request->marca}%");
        }

        if ($request->has('nombre')) {
            $query->where('Nombre', 'LIKE', "%{$request->nombre}%");
        }

        if ($request->has('todo') && $request->todo === 'true') {
            return response()->json($query->get());
        }

        // Paginado por defecto
        return response()->json($query->where("Estatus", "ALTA")
            ->orWhere("Estatus", "alta")->get());
    }
}
