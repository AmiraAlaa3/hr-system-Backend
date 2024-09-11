<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendnce extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'checkIN',
        'checkOUT',
        'employee_id',
        'work_days'

    ];

    public function attendanceAdjustments()
    {
        return $this->hasMany(EmployeeAttendanceAdjustment::class,'attendance_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}