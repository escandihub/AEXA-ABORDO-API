<?php

namespace App\Exports;

use App\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SuccessPaymentSheet implements FromCollection, WithHeadings, WithMapping
{
    private $payments;
    function __construct($collection) {
        $this->payments = $collection;
    }
    public function collection() {
        return $this->payments;
    }

    public function headings(): array
    {
        return [
            "ID transacción",
            "transaction_id",
            "metodo de pago",
            "status",
            "amount",
            "descripción",
            "realizado por",
            "fecha de creación",
        ];
    }
    public function map($row): array
    {
        return [
            $row->id,
            $row->transaction_id,
            $row->method,
            $row->status,
            $row->amount,
            $row->description,
            $row->paymentButton->user->name ?? 'N/A',
            $row->created_at
        ];
    }
}
