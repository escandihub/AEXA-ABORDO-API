<?php

namespace App\Http\Controllers\OperadoresService;

use App\Models\Client;
use App\Models\API\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\API\Device;
use App\Models\API\ExternalOperador;
use App\Models\ExternalOperador as ModelsExternalOperador;

class GetRelation 
{

    public function operadores()
    {
        return ModelsExternalOperador::select('id', 'Nombre', 'Empresa', 'Estatus')
            ->where("Empresa", "AEXPR")
            ->where("Estatus", "ALTA")
            ->orWhere("Estatus", "alta")
            ->get();
    }
}
