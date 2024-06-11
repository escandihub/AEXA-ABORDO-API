<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';
    protected $primaryKey = 'id_empleado';  

    protected $fillable = [
        'descripcion_perfil',
        'clave_terminales',
        'numero_terminal',
    ];

    
}
