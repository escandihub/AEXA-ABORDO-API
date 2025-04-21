<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class QrGenerator extends Component
{
    public $url;
    public $qrCode;

    public function render()
    {
        return view('livewire.qr-generator');
    }

    public function mount($code){
        \Log::info($code);
        $this->url = $code;
    }

    public function generateQr()
    {
        $response = Http::get('https://api.qrserver.com/v1/create-qr-code/', [
            'size' => '150x150',
            'data' => $this->url,
        ]);

        // Since this API returns a direct image URL, you can build it manually
        $this->qrCode = $response->effectiveUri(); 
    }
}
