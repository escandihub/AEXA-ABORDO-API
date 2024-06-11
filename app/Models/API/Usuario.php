<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

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
        return $this->belongsTo(Taquilla::class, 'taquilla', 'id_usuario');
    }
}
