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
            // Return the group data with both group ID and name
            'group' => [
                'id' => $this->groups->first()->id, 
                'name' => $this->groups->first()->name,
            ]
        ];
    }
}