<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
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
            'employee_id' => $this->employee_id,
            'employee_name' => $this->employee->name, // Access employee's name
            'department_name' => $this->employee->department->name, // Access department's name via employee
            'checkIN' => $this->checkIN,
            'checkOUT' => $this->checkOUT,
            'date' => $this->date,
            // 'employee_name' => $this->employee ? $this->employee->name : null,
        ];
    }
}
