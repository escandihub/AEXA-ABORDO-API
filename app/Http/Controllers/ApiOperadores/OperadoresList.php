<?php

namespace App\Http\Controllers\ApiOperadores;

use App\Http\Controllers\ApiOperadores\OperadoresPorMarca;
use App\Models\ExternalPersonal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OperadoresList extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $service = app(OperadoresService::class);
        $operadoreService = app(OperadoresPorMarca::class);

        try {
            $query = ExternalPersonal::select(["ClavePersonal AS ID, Nombre, Puesto, Empresa"]);
            if ($request->filled('marca')) {
                $query = $operadoreService->enumMarca($request->marca, $query);
                if($request->marca == 'AE'){
                    $aexaOperadores = $service->getOperadoresAexaTours();
                    return response()->json($service->mergeCollections($aexaOperadores,$query->get()));
                }
                return response()->json($query->get());
            } else{
                return response()->json(['error' => 'Marca no proporcionada'], 400);
            }
            
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 401);
        }
    }
}
