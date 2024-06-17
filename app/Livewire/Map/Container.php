<?php

namespace App\Livewire\Map;

use Livewire\Component;

use App\Models\API\Terminal;

class Container extends Component
{
    public $lat = 16.741982949707726;
    public $log = -93.10112138694628;
    public $_radio = 50;

    public function render()
    {
        return view('livewire.map.container');
    }

    public function mount(){
        
        $terminal = Terminal::where('id_terminal', 450)->first();
        // $this->lat = $terminal->latitud;
        // $this->log = $terminal->longitud;
        // $this->_radio = $terminal->radio;
    }
}
