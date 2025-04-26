<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\API\Device;

class Printer extends Model
{
    use HasFactory;

    protected $fillable = ["modelo","characteristic_uuid","service_uuid","blue_uuid"];

    /**
     * para obtener los dispositivos conectados
     * a una impresoa
     */
    public function dispositivo()
    {
    //    return $this->belongsTo(User::class, 'foreign_key', 'other_key');
       return $this->hasMany(Device::class);
    }
    public function devices()
    {
        return $this->belongsToMany(Device::class)
            ->withPivot('is_default')
            ->withTimestamps();
    }
}
