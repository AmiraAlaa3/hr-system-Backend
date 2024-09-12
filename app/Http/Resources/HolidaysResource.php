<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HolidaysResource extends JsonResource
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
            'date' => $this->date,
            'title'=> $this->title,
            'description'=> $this->description,
            'from_date'=> $this->from_date,
            'to_date'=> $this->to_date,
            'numberOfDays'=> $this->numberOfDays,
        ];
    }
}