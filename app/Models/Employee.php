<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
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
      
        return $this->hasMany(EmployeeAttendanceAdjustment::class);

    }
    public function attendances()
    {
        return $this->hasMany(Attendnce::class);
    }
    public function bonuses()
    {
        return $this->attendanceAdjustments()->whereHas('adjustment', function($query) {
                        $query->where('type', 'bouns');
                    });
    }

    public function deductions()
    {
        return $this->attendanceAdjustments()->whereHas('adjustment', function($query) {
            $query->where('type', 'dedeuction');
        });
    }


    public function getWorkDaysAttribute($month = null, $year = null)
    {
        $month = $month ?: now()->month;
    $year = $year ?: now()->year;

    return $this->attendances()->whereMonth('date', $month)->whereYear('date', $year)->distinct('date')->count('date');
    }

    public function getAbsenceDaysAttribute($month = null, $year = null)
    {
        $month = $month ?: Carbon::now()->month;
        $year = $year ?: Carbon::now()->year;
    
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();
        $period = CarbonPeriod::create($startOfMonth, $endOfMonth);
        $totalWorkDays = 0;

        foreach ($period as $date) {

            if ($date->dayOfWeek >= Carbon::SUNDAY && $date->dayOfWeek <= Carbon::THURSDAY) {
                $totalWorkDays++;
            }
        }
        
        $presentDays = $this->attendances()->whereMonth('date', $month)->whereYear('date', $year)->distinct('date')->count('date');

        return $totalWorkDays - $presentDays;
        }
    
    public function salaryPerMinute()
    {
        $workdaysPerMonth = 22;
        $workingHoursPerDay = 8;
        $totalMinutesPerMonth = $workdaysPerMonth * $workingHoursPerDay * 60;
        return $this->salary / $totalMinutesPerMonth;
    }
  
    public function totalBounsAmount($month = null, $year = null)
{
    $month = $month ?: now()->month;
        $year = $year ?: now()->year;
        $totalBonusHours = $this->totalBonusHours($month, $year);
    $bonusMinutes = $totalBonusHours * 60;
    return $bonusMinutes * $this->salaryPerMinute();
}
public function totalBonusHours($month = null, $year = null)
{
    $month = $month ?: now()->month;
     $year= $year ?: now()->year;
        return $this->attendanceAdjustments()->join('adjustments', 'employee_attendance_adjustments.adjustment_id', '=', 'adjustments.id')
                    ->where('adjustments.type', 'bouns')->whereMonth('date', $month)
                    ->whereYear('date', $year)->sum('adjustments.houres');
}

public function totalDeductionsHours($month = null, $year = null)
{
    $month = $month ?: now()->month;
    $year = $year ?: now()->year;
    return $this->attendanceAdjustments()->join('adjustments', 'employee_attendance_adjustments.adjustment_id', '=', 'adjustments.id')
                ->where('adjustments.type', 'dedeuction')->whereMonth('date', $month)
                ->whereYear('date', $year)->sum('adjustments.houres');
}
  public function totalDeductionAmount($month = null, $year = null)
{
    $month = $month ?: now()->month;
    $year = $year ?: now()->year;
    
    $totalDeductionHours = $this->totalDeductionsHours($month, $year);
    
    $deductionMinutes = $totalDeductionHours * 60;
    
    return $deductionMinutes * $this->salaryPerMinute();
}

public function totalSalaryAmount($month = null, $year = null)
{
    $month = $month ?: now()->month;
    $year = $year ?: now()->year;

    $workDays = $this->getWorkDaysAttribute($month, $year);
    $bonusAmount = $this->totalBounsAmount($month, $year);
    $deductionAmount = $this->totalDeductionAmount($month, $year);

    $salaryPerMinute = $this->salaryPerMinute();
    $totalWorkingMinutes = $workDays * 8 * 60; 
    $baseSalary = $totalWorkingMinutes * $salaryPerMinute;

    return $baseSalary + $bonusAmount - $deductionAmount;
}

}