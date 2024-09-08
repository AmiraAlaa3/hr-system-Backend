<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function Laravel\Prompts\table;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'birthdate',
        'address',
        'phone_number',
        'hire_date',
        'ssn',
        'gender',
        'nationality',
        'position',
        'Marital_status',
        'salary',
        'check_in_time',
        'check_out_time',
        'department_id'
    ];

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

    public function attendanceAdjustments()
    {
        return $this->hasMany(EmployeeAttendanceAdjustment::class,'employee_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendnce::class);
    }


}
