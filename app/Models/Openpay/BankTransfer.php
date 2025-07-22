<?php

namespace App\Models\Openpay;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransfer extends Model
{
    use HasFactory;

    protected $table = 'bank_transfers';
    protected $connection = 'openpay';
    
    protected $fillable = [
        'transaction_id',
        'type',
        'bank',
        'clabe',
        'agreement',
        'name',
        'url_spei',
        'reference',
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
