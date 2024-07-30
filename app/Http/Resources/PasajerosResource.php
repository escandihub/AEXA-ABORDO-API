<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PasajerosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "number" => $this->numero_asiento,
            "abordo" => $this->abordo,
            "color" => $this->abordo ? 'red' : 'blue',
            "currentTerminal" => $this->numero_terminal
        ];
    }

    public function generateColor($terminal, $compra_t, $abordo) {
        
        if($terminal === $compra_t && $abordo ==  0){
            return 'blue';
        }else if($terminal === $compra_t && $abordo ==  1){
            return 'green';
        }else if($terminal !== $compra_t && $abordo ==  0){
            return 'red';
        }
        return 'gray';
        }
}
