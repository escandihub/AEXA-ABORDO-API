<?php

namespace App\Models\Openpay;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';
    protected $connection = 'openpay';

    protected $fillable = [
        'name',
        'last_name',
        'phone_number',
        'email',
        'external_id'
    ];

}
