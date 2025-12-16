<?php

namespace App\Http\Controllers\ApiOperadores;

use  App\Models\API\other_operators;
use App\Models\ExternalPersonal;
use Illuminate\Database\Eloquent\Builder;

class OperadoresPorMarca 
{
    public function ObtenerPorMarca($marca){

    }

    public function enumMarca($marca, Builder $query ){
        if($marca == 'AE'){
            return $query->whereIn('Empresa', ["AEXPR","TRANS"])
            ->where(function ($query) { return $query->where('Puesto', 'LIKE', '%PERADOR%'); });
        }else if($marca == 'TI'){ // titanium
            return $query->WhereIn('Empresa', ['MEXIC', 'TITAN'])
            ->where(function ($query) { return $query->where('Puesto', 'LIKE', '%PERADOR%'); });
        }else{
             throw new \Exception('non supported indicator');
            
        }
    }
}
