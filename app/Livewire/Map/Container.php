<?php

namespace App\Livewire\Map;

use Livewire\Component;

use App\Models\API\Terminal;

class Container extends Component
{
    public $lat = 16.741982949707726;
    public $log = -93.10112138694628;
    public $_radio = 50;
    public $idTer;

    public function render()
    {
        return view('livewire.map.container');
    }

    public function mount(){

        // dd($this->idTer);
        
        // $terminal = Terminal::where('id_terminal', $this->idTer)->first();
        $this->lat = $this->idTer->latitud;
        $this->log = $this->idTer->longitud;
        $this->_radio = $this->idTer->radio;
    }
}
