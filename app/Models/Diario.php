<?php

namespace App\Models;

use App\Models\API\Pasajero;
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

    
    public function pasajero()
    {
        return $this->hasMany(Pasajero::class, 'id_diario_c', 'id_diario_c');
    }
}
