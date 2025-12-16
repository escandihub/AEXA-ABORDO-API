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
        // $service = app(OperadoresService::class);
        $operadoreService = app(OperadoresPorMarca::class);

        try {
            $query = ExternalPersonal::query();
            if ($request->has('marca')) {
                $query = $operadoreService->enumMarca($request->marca, $query);
            } {
                return response()->json(['error' => 'Marca no proporcionada'], 400);
            }
            return response()->json($query->get());
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 401);
        }
    }
}
