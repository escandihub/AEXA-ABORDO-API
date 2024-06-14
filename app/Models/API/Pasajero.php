<?php

namespace App\Models\API;

use App\Models\Diario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasajero extends Model
{
    use HasFactory;
    
    protected $table = 'pasajeros';
    protected $primaryKey = 'id_pasajero';  


    protected $fillable = [
        'abordo',
    ];
    /**
     * Get the diario that owns the Pasajero
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function diario()
    {
        return $this->belongsTo(Diario::class, 'id_diario_c', 'id_diario_c');
    }
}
