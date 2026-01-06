<?php

namespace App\Livewire\OpenPay;

use Livewire\Component;

class PaymentSuccess extends Component
{
    public function render()
    {
        return view('livewire.open-pay.payment-success')->layout('layouts.empty');
    }

    private function searchById(){
        
    }
}
