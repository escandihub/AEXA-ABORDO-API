<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taquilla extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_taquillas';

    public function user()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'taquilla');
    }
}
