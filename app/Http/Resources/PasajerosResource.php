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
            "color" => $this->abordo ? 'red' : 'blue'
        ];
    }
}
