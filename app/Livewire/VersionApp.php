<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\trakingApp;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;


class VersionApp extends Component
{
    use WithFileUploads;
    public function render()
    {
        $trainking = trakingApp::all();
        return view('livewire.version-app', [ 'versiones' => $trainking ]);
    }

    #[Validate('required')]
    public $name = '';
    #[Validate('required')]
    public $versionCode = '';
    #[Validate('required')]
    public $file;


    public function saveNewApp(){

        $name_app = $this->name . 'prueba.' . $this->file->getClientOriginalExtension();
        $name = $this->file->storeAs(path: 'updates', name: $name_app);

        $c = trakingApp::create([
            "nombre" => $this->name,
            "versionCode" => $this->versionCode,
            "versionName" => '1',
            "active" => 1,
            "in_process" => 1,
            "comentarios" => "comentario test",
            "path_app" => $name,
        ]);
        \Log::info($c);
    }
}
