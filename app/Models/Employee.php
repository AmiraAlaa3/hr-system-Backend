<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\GenralSetting;
use Carbon\CarbonPeriod;

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

    // Define relationships
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
    public function settings()
    {
        return $this->belongsToMany(GenralSetting::class,'employee_genral_setting');
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

        $setting = GenralSetting::first();
        $weekend1 = $setting->weekend1;
        $weekend2 = $setting->weekend2;

        $dayMapping = [
            'Sunday'    => Carbon::SUNDAY,
            'Monday'    => Carbon::MONDAY,
            'Tuesday'   => Carbon::TUESDAY,
            'Wednesday' => Carbon::WEDNESDAY,
            'Thursday'  => Carbon::THURSDAY,
            'Friday'    => Carbon::FRIDAY,
            'Saturday'  => Carbon::SATURDAY,
        ];

        $weekend1 = $dayMapping[$weekend1];
        $weekend2 = $dayMapping[$weekend2];

        return $this->attendances()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)

            ->where(function($query) use ($weekend1, $weekend2) {
                $query->whereRaw('WEEKDAY(date) != ?', [$weekend1-1])
                      ->whereRaw('WEEKDAY(date) != ?', [$weekend2-1]);
            })
            ->distinct('date')
            ->count('date');
    }


    public function getAbsenceDaysAttribute($month = null, $year = null)
    {
        $month = $month ?: Carbon::now()->month;
        $year = $year ?: Carbon::now()->year;

        $setting = GenralSetting::first();
        $weekend1 = $setting->weekend1;
        $weekend2 = $setting->weekend2;
        $dayMapping = [
            'Sunday'    => Carbon::SUNDAY,
            'Monday'    => Carbon::MONDAY,
            'Tuesday'   => Carbon::TUESDAY,
            'Wednesday' => Carbon::WEDNESDAY,
            'Thursday'  => Carbon::THURSDAY,
            'Friday'    => Carbon::FRIDAY,
            'Saturday'  => Carbon::SATURDAY,
        ];

        $weekend1 = $dayMapping[$weekend1];
        $weekend2 = $dayMapping[$weekend2];

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();
        $period = CarbonPeriod::create($startOfMonth, $endOfMonth);

        $totalWorkDays = 0;


        $holidays = Annual_Holidays::where(function ($query) use ($startOfMonth, $endOfMonth) {
            $query->whereBetween('from_date', [$startOfMonth, $endOfMonth])
                  ->orWhereBetween('to_date', [$startOfMonth, $endOfMonth]);
        })->get();

        // Loop through each day in the period and calculate total workdays
        foreach ($period as $date) {
            if ($date->dayOfWeek != $weekend1 && $date->dayOfWeek != $weekend2) {
                $isHoliday = false;
                foreach ($holidays as $holiday) {
                    if ($date->between(Carbon::parse($holiday->from_date), Carbon::parse($holiday->to_date))) {
                        $isHoliday = true;
                        break;
                    }
                }

                if (!$isHoliday) {
                    $totalWorkDays++;
                }
            }
        }

        $presentDays = $this->attendances()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where(function ($query) use ($weekend1, $weekend2) {
                $query->whereRaw('WEEKDAY(date) != ?', [$weekend1-1])
                ->whereRaw('WEEKDAY(date) != ?', [$weekend2-1]);
            })
            ->distinct('date')
            ->count('date');

        return $totalWorkDays - $presentDays;
    }


    public function salaryPerMinute()
    {
        $workdaysPerMonth = 22;
        $workingHoursPerDay = 8;
        $totalMinutesPerMonth = $workdaysPerMonth * $workingHoursPerDay * 60;

        return $this->salary / $totalMinutesPerMonth;
    }

    public function calculateMonthlyBonusDeduction($month = null, $year = null)
    {
        $month = $month ?: Carbon::now()->month;
        $year = $year ?: Carbon::now()->year;

        $attendances = $this->attendances()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $totalBonus = 0;
        $totalDeduction = 0;

        $workStartTime = Carbon::createFromTimeString($this->check_in_time);
        $workEndTime = Carbon::createFromTimeString($this->check_out_time);

        $setting = $this->settings()->first();
        // Get the bonus and deduction hours from settings
        $bonusHours = $setting ? $setting->bonusHours : 1;
        $deductionHours = $setting ? $setting->deductionsHours : 1;

        // Initialize counters for minutes
        $bonusMinutes = 0;
        $earlyLeaveMinutes = 0;
        $deductionMinutes = 0;

        foreach ($attendances as $attendance) {
            $checkInTime = $attendance->checkIN ? Carbon::parse($attendance->checkIN) : null;
            $checkOutTime = $attendance->checkOUT ? Carbon::parse($attendance->checkOUT) : null;

            if ($checkInTime && $checkOutTime) {
                // Deduct for late arrival
                if ($checkInTime->greaterThan($workStartTime)) {
                    $deductionMinutes += $checkInTime->diffInMinutes($workStartTime);
                }

                // Deduct for early leave, else add bonus for overtime
                if ($checkOutTime->lessThan($workEndTime)) {
                    $earlyLeaveMinutes += $workEndTime->diffInMinutes($checkOutTime);
                } elseif ($checkOutTime->greaterThan($workEndTime)) {
                    $bonusMinutes += $checkOutTime->diffInMinutes($workEndTime);
                }
            }
        }

        // Calculate the total deduction and bonus with hours applied as multipliers
        $totalBonus = ($bonusMinutes * $this->salaryPerMinute()) * $bonusHours;
        $totalDeduction = (($earlyLeaveMinutes + $deductionMinutes) * $this->salaryPerMinute()) * $deductionHours;

        return [
            'total_bonus' => $totalBonus,
            'total_deduction' => $totalDeduction,
            'bonus_hours' => $this->formatHoursMinutes($bonusMinutes),
            'deduction_hours' => $this->formatHoursMinutes($earlyLeaveMinutes + $deductionMinutes),
        ];
    }

    /**
     * Helper function to format minutes into HH:MM format
     */
    private function formatHoursMinutes($totalMinutes)
    {
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        return sprintf('%02d:%02d', $hours, $minutes);
    }


public function totalSalaryAmount($month = null, $year = null)
{
    $month = $month ?: now()->month;
    $year = $year ?: now()->year;
    $workDays = $this->getWorkDaysAttribute($month, $year);
    $AbsenceDays=$this->getAbsenceDaysAttribute($month , $year );
    $bonusDeductionData = $this->calculateMonthlyBonusDeduction($month, $year);
    $bonusAmount = $bonusDeductionData['total_bonus'];
    $deductionAmount = $bonusDeductionData['total_deduction'];
    $salaryPerMinute = $this->salaryPerMinute();
    $totalWorkingMinutes = $workDays * 8 * 60;
    $totalAbsenceDaysMinutes=$AbsenceDays* 8 * 60;

    $baseSalary = $totalWorkingMinutes * $salaryPerMinute;
    return $this->salary + $bonusAmount - $deductionAmount - ($totalAbsenceDaysMinutes* $salaryPerMinute );
    // return ($totalAbsenceDaysMinutes* $salaryPerMinute );
}

}
