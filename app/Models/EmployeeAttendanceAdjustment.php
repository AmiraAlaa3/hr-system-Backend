<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttendanceAdjustment extends Model
{
    use HasFactory;
    // protected $fillable = [
    //     'total_bouns_hours'
    // ];

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
        // return $this->belongsTo(Employee::class);

    }

    public function attendance()
    {
        return $this->belongsTo(Attendnce::class,'attendance_id');
        // return $this->belongsTo(Attendnce::class);

    }

    public function adjustment()
    {
        return $this->belongsTo(Adjustment::class,'adjustment_id');
        // return $this->belongsTo(Adjustment::class);

    }
}