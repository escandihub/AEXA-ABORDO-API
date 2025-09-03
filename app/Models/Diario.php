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
    public $timestamps = false;

    protected $fillable = [
        'capacidad',
        'disponible',
        'hora',
        'fecha', 'hora', 'minutos', 'origen', 'destino', 
        'autobus', 'clase', 'operador1', 'operador2', 'condicion_corrida'
    ];
       protected $casts = [
        'fecha' => 'date',
        'hora' => 'integer',
        'minutos' => 'integer',
    ];

    // Scope para corridas disponibles
    public function scopeDisponibles($query)
    {
        return $query->where('condicion_corrida', 'Disponible')
                    ->where('clase', '!=', 3);
    }
       // Scope para filtro de fechas
    public function scopeEntreFechas($query, $fechaInicio, $fechaFin)
    {
        if ($fechaInicio && $fechaFin) {
            return $query->whereBetween('fecha', [
                \Carbon\Carbon::parse($fechaInicio)->format('Y-m-d'),
                \Carbon\Carbon::parse($fechaFin)->format('Y-m-d')
            ]);
        }
        return $query;
    }

     // Scope para búsqueda de texto
    public function scopeBuscar($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('autobus', 'like', '%' . $search . '%')
                  ->orWhere('operador1', 'like', '%' . $search . '%');
            });
        }
        return $query;
    }
    
     // Scope para filtro por hora actual
    public function scopeHoraActual($query)
    {
        $now = \Carbon\Carbon::now();
        $horaInicio = $now->subMinutes(30)->hour;
        $horaFin = $now->addMinutes(60)->hour; // +60 porque ya restamos 30
        
        return $query->where(function ($q) use ($horaInicio, $horaFin, $now) {
            // Si el rango no cruza la medianoche
            if ($horaInicio <= $horaFin) {
                $q->whereBetween('hora', [$horaInicio, $horaFin]);
            } else {
                // Si cruza la medianoche (ej: 23:30 - 00:30)
                $q->where('hora', '>=', $horaInicio)
                  ->orWhere('hora', '<=', $horaFin);
            }
        });
    }

    
    public function pasajero()
    {
        return $this->hasMany(Pasajero::class, 'id_diario_c', 'id_diario_c');
    }
}
