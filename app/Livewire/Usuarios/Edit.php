<?php

namespace App\Livewire\Usuarios;

use App\Models\API\Usuario;

use Livewire\Component;

class Edit extends Component
{

    public Usuario $usuario ;
    public function render()
    {

       
        // $this->usuario = $usuario;
        return view('livewire.usuarios.edit');
    }

    public function mount(){
      $this->usuario = Usuario::find(3);
    }

    public function update()
    {
        $this->usuario->update();
        session()->flash('success','Se ha actulizado el usuario exitosamente');
    }

    public function closeChild(){
        $this->dispatch('set-view'); 
    }
    
}
