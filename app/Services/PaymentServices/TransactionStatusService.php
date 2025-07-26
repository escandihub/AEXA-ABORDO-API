<?php

namespace App\Services\PaymentServices;

use App\Models\Openpay\TransactionLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Openpay\Transaction;



class TransactionStatusService 
{
    public function transaction($order_id){
        return Transaction::where('order_id', $order_id)->first();
    }
}