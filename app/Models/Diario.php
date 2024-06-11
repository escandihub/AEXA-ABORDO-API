<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diario extends Model
{
    use HasFactory;

    protected $table = 'diario_c';
    protected $primaryKey = 'id_diario_c';  

    protected $fillable = [
        'capacidad',
        'disponible',
        'hora',
        'fecha',
    ];
}
