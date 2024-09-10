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

    #[Validate('required|')]
    public $name = '';
    #[Validate('required|')]
    public $versionCode = '';
    public $files;
}
