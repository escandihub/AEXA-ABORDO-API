<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalPersonal extends Model
{
    protected $connection = 'sqlsrv_intelisis';

    protected $table = 'Personal';

    protected $fillable = [
        'ClavePersonal',
        'Nombre',
        'Puesto',
    ];
}
