<?php

namespace App\Models\Openpay;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorePayment extends Model
{
    use HasFactory;

     protected $table = 'store_payments';

    protected $connection = 'openpay';


     protected $fillable = [
        'transaction_id',
        'type',
        'reference',
        'barcode_url',
        'url_store',
        'store_name',
        'expires_at',
    ];

     protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
