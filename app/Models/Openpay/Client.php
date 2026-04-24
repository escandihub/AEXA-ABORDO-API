<?php

namespace App\Models\Openpay;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

     protected $fillable = ['contacto_id', 'name', 'lastname'];

      public function contacto()
    {
        return $this->belongsTo(Customer::class);
    }
    public function getFullNameAttribute(): string
    {
        return trim("{$this->name} {$this->lastname}");
    }
}
