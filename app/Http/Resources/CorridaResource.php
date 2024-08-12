<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class CorridaResource extends JsonResource
{

    public static $wrap = null;
    /**
     * Se manejara dos tipos de corridaResource
     * 1ra. obtiene la hora de diario_c
     * 2rda. obtiene la hora de pasajeros (pasajero que va abordar)
     */
    public $type = 1;
    public $resource;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function __construct($resource, $type = 1) {
        // Ensure you call the parent constructor
        parent::__construct($resource);
        $this->resource = $resource;
        $this->type = $type;
        // dd($this);
    }

    public function toArray(Request $request): array
    {

        // dd($this);
        // return parent::toArray($request);
        // \Log::info($this->type);
        return [
            'id' => $this->id_diario_c,
            "origen" => $this->origen,
            "destino" => $this->destino,
            "clase" => $this->clase,
            "bus" => [
                "card_code" => $this->autobus,
                "capacidad" => $this->capacidad,
                "disponibilidad" => $this->disponibles
            ],
            // "hora" =>   $this->type == 1 ? "{$this->resource->hora}:{$this->resource->minutos}" : "{$this->pHora}:{$this->pMinutos}",
            "hora" => "{$this->hora}:{$this->minutos}",
            "fecha" => $this->fecha
        ];
    }
}
