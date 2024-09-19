<?php

namespace App\Livewire\Map;

use Livewire\Component;
use App\Models\API\Terminal as TerminalModel;
use App\Models\API\Taquilla;
use Livewire\Attributes\On;

class ModalForm extends Component
{
    public $showV = false;
    public $terminal;

    protected $listeners = ['render-map' => 'RenderMap'];

    public function render()
    {
        return view('livewire.map.modal-form');
    }

    public function RenderMap(Taquilla $terminal_id)
    {
        $this->terminal = $terminal_id;
        $this->showV = true;
    }

    public function close(){
        $this->showV = false;
    }
    // cerrar el modal del mapa mediante un evento
    #[On('close-modal-map')] 
    public function cerrar(){
        $this->showV = false;
    }
}
