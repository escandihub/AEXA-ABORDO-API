<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiarioCiudad extends Model
{
    use HasFactory;

    protected $table = 'diario_c_ciudades';
    protected $primaryKey = 'id_diario_c';  
}
