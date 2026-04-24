<?php

namespace App\Models\Openpay;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

     public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function scopeByEmailOrPhone(Builder $query, ?string $email, ?string $phone)
    {
        return $query->where(function (Builder $q) use ($email, $phone) {
            if ($email) $q->orWhere('email', $email);
            if ($phone) $q->orWhere('phone_number', $phone);
        });
    }
}
