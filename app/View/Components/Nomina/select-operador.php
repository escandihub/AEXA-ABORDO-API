<?php

namespace App\View\Components\Nomina;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class selectOperador extends Component
{
    /**
     * Create a new component instance.
     */
    public $operadores;
    public function __construct($operadores)
    {
        $this->operadores = $operadores;
    }
    

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.nomina.select-operador');
    }
}
