<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\DocumentationType;

class PasajeroDocumentacionCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        \Log::info($request);
        return [
            "id" => $this->id,
            "uuid" => $this->uuid,
            "status" => $this->status,
            "tipo" => DocumentationType::select('name')->find($this->type_id)["name"]
        ];
        //return parent::toArray($request);
    }
}
