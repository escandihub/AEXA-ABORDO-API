<?php

namespace  App\Livewire\OpenPay\repository;

use App\Livewire\OpenPay\service\Contracts\PaymentConfigInterface;
use App\Models\Openpay\Transaction;
use App\Livewire\OpenPay\Dtos\SuccessCardPay;

class FindTransactionService
{
    public function find(string $id): ?SuccessCardPay {
          $transaction = Transaction::where('transaction_id', $id)
          ->with(['paymentButton'])->first();

          if(!$transaction){
            return new SuccessCardPay(
                 nombre: 'mi nombre',
                apellido: 'apellido',
                monto: 0
            );
          }

          $customer = $transaction->paymentButton->customer;

          if(!$customer){
            return new SuccessCardPay(
                 nombre: 'mi nombre',
                apellido: 'apellido',
                monto: 0
            );
          }
        
          return new SuccessCardPay(
            nombre: $customer->name,
            apellido: $customer->last_name,
            monto: $transaction->amount
          );
    }
} 