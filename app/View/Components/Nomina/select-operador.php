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
    public $default;
    public $diario;
    public function __construct($operadores, $default, $diario = "")
    {
        \Log::info($diario);
        $this->default = $default;
        $this->diario = $diario;
        $this->operadores = $operadores;
    }
    

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.nomina.select-operador');
    }

    /**
     * Update the default operador.
     *
     * @param  string  $corrida - Como saber si es operador1 o operador2
     * @return void
     */
    public function updateOperador($operador)
    {
        $this->default = $operador;
    }
}
