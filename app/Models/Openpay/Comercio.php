<?php

namespace App\Models\Openpay;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comercio extends Model
{
    use HasFactory;

    protected $connection = 'openpay';

    protected $fillable = [
        'nombre',
        'slug'
    ];


    function pagos() {
        return $this->hasMany(payment::class, 'comercio_id', 'id');
    }
}
