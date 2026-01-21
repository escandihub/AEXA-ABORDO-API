<?php

namespace App\Livewire\OpenPay\service;
use App\Models\Openpay\payment;
use App\Models\Openpay\Transaction;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuccessPaymentSheet;
// facturacion1@ddtech.mx 

class ExportSales {

   private string|array $date;
   private $collection;

   public function findbyDate(array $date){
    $this->date = $date;
    $this->collection = Transaction::with('paymentButton.user')->whereBetween('created_at', [$date['start'], $date['end']])
    ->where('status', 'completed')
    ->get();
   }

   public function export(){

    $export = new SuccessPaymentSheet($this->collection);
    $dinamic_name = $this->date['start'] . "_a_" . $this->date['end'];
    return Excel::download($export, "{$dinamic_name}_ventas_link_pago.xlsx",\Maatwebsite\Excel\Excel::XLSX);
   }
}

/**
  App\Models\Openpay\Transaction::with('paymentButton.user')->whereBetween('created_at', ["2025-07-20", "2025-07-23"])->where('status', 'completed')->get();
 */