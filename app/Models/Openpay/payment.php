<?php

namespace App\Models\Openpay;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    use HasFactory;

    protected $table = 'payments_buttons';

    protected $connection = 'openpay';

    protected $fillable = [
        'openpay_id',
        'customer_id',
        'amount',
        'description',
        'authorization',
        'method',
        'operation_type',
        'order_id',
        'currency',
        'iva',
        'status',
        'checkout_link',
        'creation_date',
        'processed_at',
        'expiration_date'
    ];

    public function customer()
    {
        return $this->belongsTo(customer::class, 'customer_id');
    }

    public function scopeFilterByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeFilterByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    public function scopeFilterByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('creation_date', [$startDate, $endDate]);
    }
    public function scopeJoinCustomer($query)
    {
        return $query->join('customers', 'payments.customer_id', '=', 'customers.id')
        ->selectRaw("payments.*, CONCAT(customers.name, ' ', customers.last_name) AS cliente");
            // ->select('payments.*', "customers.name as cliente", 'customers.phone_number', 'customers.email as customer_email');
    }
}
