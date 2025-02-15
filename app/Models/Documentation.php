<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documentation extends Model
{
    use HasFactory;

    protected $fillable = [
        'pasajero_id',
        'status',
        'documenter_by',
        'delivery_by',
        'uuid',
        'type_id',
        'number_document',
        'delivery_at'
    ];

    public function type() {
        return $this->belongsTo(DocumentationType::class, 'type_id', 'id');
    }
}
