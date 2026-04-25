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
                monto: 0,
                status: 'trans'
            );
          }

          $customer = $transaction->paymentButton->customer;

          // verificar el id de la transaccion el status de la operacion 
          // indicativo exitoso o no. 

          if(!$customer){
            return new SuccessCardPay(
                 nombre: 'mi nombre',
                apellido: 'apellido',
                monto: 0,
                status: 'cliente'
            );
          }

          $estado = $this->mapStatus($transaction);
          \Log::info($estado);

          return new SuccessCardPay(
            nombre: $customer->name,
            apellido: $customer->last_name,
            monto: $transaction->amount,
            status: $estado,
            concepto: $transaction->description
          );
    }

    private function mapStatus($transaction) {
      if($transaction?->status == 'completed'){
        return true;
       }
       else if($transaction?->logs()?->latest()->first()?->status){
        return false;
       }
       else {
        return false;
       }
      }
    }