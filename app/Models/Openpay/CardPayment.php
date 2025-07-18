<?php

namespace App\Models\Openpay;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardPayment extends Model
{
    use HasFactory;

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
