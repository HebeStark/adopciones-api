<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdoptionRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status->value,

            'animal' => [
                'id' => $this->animal->id,
                'nombre' => $this->animal->nombre,
                'estado' => $this->animal->estado->value,
            ],

            'adopter' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],

            'created_at' => $this->created_at,
        ];
    }
}
