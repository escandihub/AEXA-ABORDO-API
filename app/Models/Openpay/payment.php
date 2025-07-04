<?php

namespace App\Models\Openpay;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'openpay_id',
        'customer_id',
        'amount',
        'description',
        'order_id',
        'currency',
        'iva',
        'status',
        'checkout_link',
        'creation_date',
        'expiration_date'
    ];
}
