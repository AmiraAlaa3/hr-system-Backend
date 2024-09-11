<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'birthdate' => $this->birthdate,
            'address' => $this->address,
            'phone_number' => $this->phone_number,
            'hire_date' => $this->hire_date,
            'ssn' => $this->ssn,
            'gender' => $this->gender,
            'nationality' => $this->nationality,
            'position' => $this->position,
            'Marital_status' => $this->Marital_status,
            'salary' => $this->salary,
            'check_in_time' => $this->check_in_time,
            'check_out_time' => $this->check_out_time,
            'department' => [
                'id' => $this->department ? $this->department->id : null,
                'name' => $this->department ? $this->department->name : null,
            ],
        ];
    }
}