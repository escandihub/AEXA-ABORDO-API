<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Passanger extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $diario = $this->diario;
        return [
            "id" => $this->id_pasajero78, // id de pasajero
            "folio" => $this->consecutivo_terminal,
            "nombre" => $this->nombre,
            "ruta" => "{$this->origen}-{$this->destino}",
            "asiento" => $this->numero_asiento,
            "bus" => $diario->autobus
        ];
    }
}
