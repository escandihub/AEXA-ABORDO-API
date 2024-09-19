<?php

namespace App\Livewire\Map;

use Livewire\Component;

use App\Models\API\Terminal;
use Livewire\Attributes\On; 
use Illuminate\Support\Facades\Log;
use App\Models\API\Taquilla;

class Container extends Component
{
    public $lat = 16.741982949707726;
    public $log = -93.10112138694628;
    public $_radio = 50;
    public $idTer;

    // protected $listeners = ['new-circle' => 'updateRadio'];

    public function render()
    {
        return view('livewire.map.container');
    }

    public function mount(){

      //  dd($this->idTer);
        
        // $terminal = Terminal::where('id_terminal', $this->idTer)->first();
         $this->lat = $this->idTer->latitud;
         $this->log = $this->idTer->longitud;
         $this->_radio = $this->idTer->radio;
    }



    /**
     * La funcion de leaflet retorma los datos en metros
     * 1 kilometro = 1000 mentros 
     */
    #[On('new-circle')] 
    public function handleCircle($lat = null, $log = null, $radio = null)  {
        Log::info($lat);

        $this->idTer->update([
        'latitud' => $lat,
        'longitud' => $log,
        'radio' => $radio
        ]);
    }
}
