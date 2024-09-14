<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeAttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "employee_name" => $this->name,
            "department_name" => $this ->department->name,
            "Attendance_time" => $this->attendances -> first()->checkIN ?? null,
            "Dismissal_time" => $this->attendances-> first()->checkOUT ?? null,
             "date"=> $this->attendances -> first()->date ?? null,
        ];
    }
}
