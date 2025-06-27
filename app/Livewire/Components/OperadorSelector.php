<?php

namespace App\Livewire\Components;

use Livewire\Component;

class OperadorSelector extends Component
{
      // Props
    public $operadores = [];
    public $default = '';
    public $diario = null;
    
    // Component state
    public $open = false;
    public $language = '';
    public $searchTerm = '';
    public $filteredOperadores = [];
    
    protected $listeners = ['refreshOperadores'];
    
    public function mount($operadores = [], $default = '', $diario = null)
    {
        $this->operadores = $operadores;
        $this->default = $default;
        $this->diario = $diario;
        $this->language = $default;
        $this->searchTerm = $default;
        $this->filteredOperadores = $operadores;
        
        $this->dispatch('component-mounted', ['diario' => $this->diario]);
    }
    
    public function updatedSearchTerm()
    {
        $this->filterOperadores();
    }
    
    public function updatedLanguage()
    {
        $this->filterOperadores();
    }
    
    public function toggle()
    {
        $this->open = !$this->open;
        if ($this->open) {
            $this->searchTerm = '';
            $this->language = '';
            $this->filteredOperadores = $this->operadores;
        }
    }
    
    public function filterOperadores()
    {
        if (empty($this->searchTerm)) {
            $this->filteredOperadores = $this->operadores;
            return;
        }
        
        $this->filteredOperadores = array_filter($this->operadores, function($operador) {
            return stripos($operador['nombre'], $this->searchTerm) !== false;
        });
    }
    
    public function setLanguage($nombre)
    {
        $this->language = $nombre;
        $this->searchTerm = $nombre;
        $this->open = false;
        $this->nameSelected($nombre);
    }
    
    public function nameSelected($name)
    {
        // Dispatch events to parent components or JavaScript
        $this->dispatch('task-updating', message:  'Actualizando...');
        $this->dispatch('name-selected', diario: $this->diario,
            name: $name);
    }
    
    public function cambiar()
    {
        // This method can be used for additional logic when changing diario
        $this->dispatch('diario-changed', ['diario' => $this->diario]);
    }
    
    public function refreshOperadores($operadores)
    {
        $this->operadores = $operadores;
        $this->filteredOperadores = $operadores;
    }
    
    public function render()
    {
        return view('livewire.components.operador-selector');
    }
}
