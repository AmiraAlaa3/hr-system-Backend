<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'Full_name'=> $this->Full_name,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'group' => [
                'id' => $this->groups ? $this->groups->id : null,
                'name' => $this->groups ? $this->groups->name : null,
            ],

        ];
    }
}
