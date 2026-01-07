<?php

namespace App\Livewire\OpenPay;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Livewire\OpenPay\repository\FindTransactionService;
use App\Livewire\OpenPay\Dtos\SuccessCardPay;

class PaymentSuccess extends Component
{
     #[Url]
    public $id = '';
    public $pasajero;

    public function render()
    {
        return view('livewire.open-pay.payment-success',
    //    [ "customer" => $this->pasajero]
        )->layout('layouts.empty');
    }

    public function mount(FindTransactionService $findTransactionService){
        // dd($this->id);
        $service = $findTransactionService->find($this->id);
        $this->pasajero = (object)  $service->jsonSerialize();
        // dd($service);
    }
}
