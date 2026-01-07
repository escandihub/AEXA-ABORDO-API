<?php

namespace App\Models\Openpay;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $connection = 'openpay';


    protected $fillable = [
        'transaction_id',
        'customer_id',
        'order_id',
        'method',
        'status',
        'amount',
        'currency',
        'description',
        'metadata',
        'created_at_openpay',
    ];

    protected $casts = [
        'metadata' => 'array',
        'amount' => 'decimal:2',
        'created_at_openpay' => 'datetime',
    ];

    /**
     * Relación con pagos con tarjeta
     */
    public function cardPayment(): HasOne
    {
        return $this->hasOne(CardPayment::class);
    }

    /**
     * Relación con transferencias bancarias
     */
    public function bankTransfer(): HasOne
    {
        return $this->hasOne(BankTransfer::class);
    }

    /**
     * Relación con pagos en tienda
     */
    public function storePayment(): HasOne
    {
        return $this->hasOne(StorePayment::class);
    }
    public function paymentButton(): HasOne
    {
        return $this->hasOne(payment::class, 'order_id', 'order_id');
    }

    /**
     * Obtener el método de pago específico basado en el tipo
     */
    public function getPaymentMethodAttribute()
    {
        return match ($this->method) {
            'card' => $this->cardPayment,
            'bank_transfer' => $this->bankTransfer,
            'store' => $this->storePayment,
            default => null,
        };
    }

    /**
     * Scope para filtrar por método de pago
     */
    public function scopeByMethod($query, string $method)
    {
        return $query->where('method', $method);
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
     public function logs()
    {
        return $this->hasMany(TransactionLog::class);
    }

    /**
     * Relación para obtener solo los logs de fallos
     */
    public function failedLogs()
    {
        return $this->hasMany(TransactionLog::class)->where('status', 'failed');
    }
    /**
     * scope para agrupar transacciones y relacionar con logs
     */
    public function scopeWithLogs($query){
        return $query->with(['logs' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);
    }
}