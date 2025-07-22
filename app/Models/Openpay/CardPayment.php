<?php

namespace App\Models\Openpay;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardPayment extends Model
{
    use HasFactory;
    protected $table = 'card_payments';
    protected $connection = 'openpay';

     protected $fillable = [
        'transaction_id',
        'autoruzation',  
        'type',
        'url',
        'brand',
        'card_number',
        'holder_name',
        'expiration_month',
        'expiration_year',
        'authorization',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
