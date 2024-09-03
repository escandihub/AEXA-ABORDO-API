<?php

namespace App\Livewire;

use App\Models\API\Terminal as TerminalModel;
use App\Models\API\Taquilla;

use Livewire\Component;

class Terminal extends Component
{
    protected $listeners = ['close-map' => 'closeMap'];

    public function render()
    {
        $terminal = Taquilla::select('usuarios.user', 'taquillas.terminal', 'taquillas.abreviacion', 'taquillas.id_taquillas')
        ->join('usuarios', 'usuarios.id_usuario','=','taquillas.taquilla')->get();
        return view('livewire.terminal', ["terminales" => $terminal]);
    }

    /**
     * Se encarga de emitir un evento para renderizar el modal
     */
    public function showMap($id){
        $this->dispatch('render-map', terminal_id: $id);
    }

    public function closeMap(){
        $this->dispatch('showNotification');
    }
}
