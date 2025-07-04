<?php

namespace App\Models\Openpay;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    use HasFactory;
    protected $table = 'customers';

    protected $fillable = [
        'name',
        'last_name',
        'phone_number',
        'email',
        'external_id'
    ];

}
