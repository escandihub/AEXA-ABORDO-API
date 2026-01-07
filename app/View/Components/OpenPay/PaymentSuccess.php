<?php

namespace App\View\Components\OpenPay;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Livewire\OpenPay\Dtos\SuccessCardPay;



class PaymentSuccess extends Component
{
    /**
     * Create a new component instance.
     */
    public $nombre = "alexd";
    public $apellido = "diaz";
    public $monto = 100;
    public $comentario = "";
    public $pasajero;

    public function __construct($pasajero)
    {
        // dd($pasajero);
       $this->pasajero = $pasajero;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {      
        return view('components.open-pay.payment-success');
    }
}
