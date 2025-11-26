<?php

namespace App\Http\Controllers\ApiOperadores;

use  App\Models\API\other_operators;

// other_operators
class OperadoresService
{
    /**
     * 		"ID": "1",
		"Personal": "000020",
		"Nombre": "EUSEBIO CRUZ MEJIA",
		"Empresa": "AEXPR",
		"Estatus": "ALTA"
     */
    public function getOperadoresAexaTours()
    {
        $operadores = other_operators::all();
        return $operadores->map(function ($operador) {
            return [
                'ID' => $operador->id,
                'Personal' => str_pad($operador->id_pasajero, 6, '0', STR_PAD_LEFT),
                'Nombre' => $operador->nombre,
                'Empresa' => "TOURS",
                'Estatus' => "ALTA"
            ];
        });
    }

    public function mergeCollections($collection1, $collection2)
    {
        return $collection1->merge($collection2);
    }
}
