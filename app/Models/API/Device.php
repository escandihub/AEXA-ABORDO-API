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

    public function location() {
        return $this->hasMany(DeviceLocation::class);
    }
    public function printers() {
        return $this->belongsToMany(Printer::class)
         ->withPivot('is_default')
            ->withTimestamps();
    }
    // Método para asociar una impresora
    public function associatePrinter(Printer $printer, $isDefault = false)
    {
        $this->printers()->syncWithoutDetaching([
            $printer->id => ['is_default' => $isDefault]
        ]);
    }
}
