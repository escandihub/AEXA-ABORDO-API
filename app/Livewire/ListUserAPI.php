<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\API\Empleado;

class ListUserAPI extends Component
{
    public function render()
    {
        return view('livewire.list-user-api',  [
            'users' => Empleado::all()
        ]);
    }
}
