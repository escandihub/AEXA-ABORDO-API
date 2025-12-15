<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToArray;

/**
 * Operador, Operador Primera, operador Gran Expreso, Operador Primera Clase, Operador, Operador Aexa Light,
 * MEXIC, TRANS, AEXPR, AEXPR, 
 * ---
 * MEXIC
 * OPERADOR DE AUTOBUS
 * OPERADOR
 * Operador Servicio Imss
 * OPERADOR UNIDAD IMSS
 * 
 * TRANS
 * OPERADOR
 * OPERADOR PRIMERA
 */

class ExternalPersonal extends Model
{
    protected $connection = 'sqlsrv_intelisis';

    protected $table = 'Personal';

    protected $fillable = [
        'ClavePersonal',
        'Nombre',
        'Puesto',
        'Empresa'
    ];
}
