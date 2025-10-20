<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiToken extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'token', 'active'];

     protected static function booted()
    {
        static::creating(function ($token) {
            if (!$token->token) {
                $token->token = Str::random(60);
            }
        });
    }
     public function user()
    {
        return $this->belongsTo(User::class);
    }
}
