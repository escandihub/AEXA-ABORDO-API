<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';
    protected $primaryKey = 'id_empleado';  

    protected $fillable = [
        'descripcion_perfil',
        'clave_terminales',
        'numero_terminal',
    ];

    public function termi()
    {
        return $this->hasOne(Terminal::class, 'clave_terminales', 'id_terminal');
    }
    public function terminal()
    {
        return $this->hasOne(Terminal::class, 'clave', 'clave_terminales');
    }
       
    public function taquilla()
    {
        return $this->hasOne(Taquilla::class, 'taquilla', 'id_empleado');
    }
       
}
