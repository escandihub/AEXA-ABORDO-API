<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalOperador extends Model
{
    protected $connection = 'sqlsrv_intelisis'; 

    protected $table = 'Operadores'; 
      protected $fillable = [
         'id',
         'Nombre',
            'Empresa',
            'Estatus'
    ];
    // Si tu tabla no tiene timestamps (created_at, updated_at)
    public $timestamps = false;
 }