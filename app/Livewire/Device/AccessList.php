<?php

namespace App\Livewire\Device;

use Livewire\Component;

use App\Models\API\DeviceLocation;

class AccessList extends Component
{
    public function render()
    {
        $location = DeviceLocation::where('device_id', 24)->get();
        return view('livewire.device.access-list', [
            'locations' => $location 
        ]);
    }

    
}
