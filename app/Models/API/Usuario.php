<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Model 
{
    use HasFactory, HasApiTokens;

    protected $primaryKey = 'id_usuario';

    /**
     * Autenticación por user - password
     */
    protected $fillable = [
        'user',
        'pass',
        'status',
    ];

    public function taquilla()
    {
        return $this->hasOne(Taquilla::class, 'taquilla', 'id_empleado');
    }
    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'id_empleado', 'id_empleado');
    }
    
    public function access()
    {
        return $this->belongsTo(DeviceLocation::class, 'user_id', 'id_usuario');
    }
    
}
