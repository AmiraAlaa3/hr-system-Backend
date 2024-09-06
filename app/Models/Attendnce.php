<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendnce extends Model
{
    use HasFactory;
    public function attendanceAdjustments()
    {
        return $this->hasMany(EmployeeAttendanceAdjustment::class,'attendance_id');
    }
}
