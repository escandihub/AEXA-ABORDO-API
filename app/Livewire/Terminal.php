<?php

namespace App\Livewire;

use App\Models\API\Terminal as TerminalModel;

use Livewire\Component;

class Terminal extends Component
{
    protected $listeners = ['close-map' => 'closeMap'];

    public function render()
    {
        return view('livewire.terminal', ["terminales" => TerminalModel::all()]);
    }

    /**
     * Se encarga de emitir un evento para renderizar el modal
     */
    public function showMap($id){
        $this->dispatch('render-map', terminal_id: $id);
    }

    public function closeMap(){

    }
}
