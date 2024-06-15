<?php

namespace App\Livewire;

use App\Models\API\Terminal as TerminalModel;

use Livewire\Component;

class Terminal extends Component
{
    public function render()
    {
        return view('livewire.terminal', ["terminales" => TerminalModel::all()]);
    }
}
