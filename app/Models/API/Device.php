<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\devicesTraking;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'usuario_id',
        'model',
        'plataform',
        'operatingSystem',
        'osVersion',
        'isVirtual',
        'diskFree'
    ];


    public function trakingDevice()
    {
        return $this->hasOne(devicesTraking::class);
    }
}
