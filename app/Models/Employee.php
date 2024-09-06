<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function Laravel\Prompts\table;

class Employee extends Model
{
    use HasFactory;
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function annual_holidays()
    {
        return $this->belongsToMany(Annual_Holidays::class,'employee_annual_holiyday');
    }
        public function users()
        {
            return $this->belongsToMany(User::class,'hrs_empolyees');
        }
    public function weekend()
    {
        return $this->belongsToMany(Weekend::class,'employee_weekend');
    }
    public function casual_holidays()
    {
        return $this->hasMany(Casual_Holidays::class, 'employee_id');
    }

    public function attendanceAdjustments()
    {
        return $this->hasMany(EmployeeAttendanceAdjustment::class,'employee_id');
    }


}