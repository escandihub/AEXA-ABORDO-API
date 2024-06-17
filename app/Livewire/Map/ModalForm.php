<?php

namespace App\Livewire\Map;

use Livewire\Component;
use App\Models\API\Terminal as TerminalModel;

class ModalForm extends Component
{
    public $showV = false;
    public $terminal;

    protected $listeners = ['render-map' => 'RenderMap'];

    public function render()
    {
        return view('livewire.map.modal-form');
    }

    public function RenderMap(TerminalModel $terminal_id)
    {
        // dd($terminal_id);
        $this->terminal = $terminal_id;
        $this->showV = true;
    }
}
