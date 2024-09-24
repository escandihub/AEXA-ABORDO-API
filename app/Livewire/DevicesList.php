<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\API\Device;

class DevicesList extends Component
{
    public function render()
    {
        $devices = Device::all();
        return view('livewire.devices-list', [
            "devices" => $devices
        ]);
    }
}
