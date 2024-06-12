<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class CorridaResource extends JsonResource
{

    public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        // dd($this);
        // return parent::toArray($request);
        return [
            'id' => $this->id_diario_c,
            "origen" => $this->origen,
            "destino" => $this->destino,
            "bus" => [
                "capacidad" => $this->capacidad,
                "disponibilidad" => $this->disponibles
            ],
            "hora" => "{$this->hora}:{$this->minutos}",
            "fecha" => $this->fecha
        ];
    }
}
