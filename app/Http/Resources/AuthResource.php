<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource as UserResourceClass;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
            'user' => new UserResourceClass($this->resource['user']),
            'access_token' => $this->resource['access_token'],
            'token_type' => $this->resource['token_type'],
        ];
    }
}
