<?php

namespace App\Models\Openpay;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    use HasFactory;

    protected $table = 'transaction_logs';

    protected $connection = 'openpay';

    protected $fillable = [
        'transaction_id',
        'status',
        'error_message',
        'error_details',
        'gateway_response_code',
        'attempted_amount',
    ];

    protected $casts = [
        'error_details' => 'array',
        'attempted_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['date'];

    /**
     * Relación con la transacción
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Scope para obtener solo logs de fallos
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope para obtener logs por rango de fechas
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Método estático para crear un log de fallo
     */
    public static function logFailure(
        int $transactionId,
        string $errorMessage,
        array $errorDetails = null,
        string $gatewayCode = null,
        float $amount = null
    ): self {
        return self::create([
            'transaction_id' => $transactionId,
            'status' => 'failed',
            'error_message' => $errorMessage,
            'error_details' => $errorDetails,
            'gateway_response_code' => $gatewayCode,
            'attempted_amount' => $amount,
        ]);
    }

    /**
     * Método para obtener un resumen legible del error
     */
    public function getErrorSummary(): string
    {
        $summary = "Error en transacción #{$this->transaction_id}";
        $summary .= " - {$this->error_message}";

        if ($this->gateway_response_code) {
            $summary .= " (Código: {$this->gateway_response_code})";
        }

        $summary .= " - {$this->created_at->format('d/m/Y H:i:s')}";

        return $summary;
    }
    public function getDateAttribute() {
    return $this->created_at->format('Y-m-d H:i');
    }
}
