<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttendanceAdjustment extends Model
{
    use HasFactory;
    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    public function attendance()
    {
        return $this->belongsTo(Attendnce::class,'attendance_id');
    }

    public function adjustment()
    {
        return $this->belongsTo(Adjustment::class,'adjustment_id');
    }
}
