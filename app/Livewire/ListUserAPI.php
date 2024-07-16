<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\API\Empleado;

class ListUserAPI extends Component
{
    public $view = '';
    protected $listeners = ['set-view' => 'setView'];
    public function render()
    {
        return view('livewire.list-user-api',  [
            'users' => Empleado::all()
        ]);
    }

    public function update(){
        $this->view = "update";
    }

    public function setView(){
        $this->view = '';
    }
}
