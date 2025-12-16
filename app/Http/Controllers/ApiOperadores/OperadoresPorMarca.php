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
            return $query->whereNotIn('Empresa', ["MEXIC","TRANS"])
            ->where(function ($query) { return $query->where('Puesto', 'LIKE', '%PERADOR%'); });
        }else if($marca == 'TI'){ // titanium
            return $query->Where('Empresa', 'TRANS')->OrWhere('Empresa', 'MEXIC')
            ->where(function ($query) { return $query->where('Puesto', 'LIKE', '%PERADOR%'); });
        }else{
             throw new \Exception('non supported indicator');
            
        }
    }
}
