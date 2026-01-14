<?php

namespace App\Livewire\OpenPay\service;

use  App\Models\Openpay\Comercio;

class ComercioService
{

    function GetComercio()
    {
       return Comercio::all()->transform(function ($item, $key) {
            return [
                'id' => $item->id,
                'name' => $item->comercio,
                'tag' => $item->tag,
                'brands' => explode(",", $item->img_text)
            ];
        })->toJson();
    }
}
