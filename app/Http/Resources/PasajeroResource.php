<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PasajeroResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $name = preg_split("/[-.]+/", $this->nombre); 
        return [
            "id" => $this->id_pasajero,
            "nombre" =>  $name[0],
            "folio" => $this->folio_empleado,
            "escala" => $this->escala,
            "status" => $this->status,
            "abordo" => $this->abordo,
            "asiento" => $this->numero_asiento,
            "tipo" => $this->tipo_descuento
        ];
    }
}
