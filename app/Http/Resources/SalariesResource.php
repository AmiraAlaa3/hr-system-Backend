<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalariesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $month = $request->input('month');
        $year = $request->input('year');
        return [
            'name' => $this->name,
            'salary' => $this->salary,
            'checkin' => $this->check_in_time ?? null,
            // 'date' => $month ?? null,
            'checkOUT' => $this->check_out_time ?? null,
            'work_days' =>  $this->getWorkDaysAttribute($month, $year),  // Using the dynamic value or fallback
            'absence_days' => $this->getAbsenceDaysAttribute($month, $year),
            'total_bonus_hours' => $this->calculateMonthlyBonusDeduction($month, $year)['bonus_hours'],
            'total_deduction_hours' => $this->calculateMonthlyBonusDeduction($month, $year)['deduction_hours'],
            'bonus_amount' => $this->calculateMonthlyBonusDeduction($month, $year)['total_bonus'],
            'deductions_amount' => $this->calculateMonthlyBonusDeduction($month, $year)['total_deduction'],
            'salary_cal'=>$this->salaryPerMinute(),
            'total_salary'=>$this->totalSalaryAmount($month , $year),
            'department' => [
                                'name' => $this->department ? $this->department->name : null,
                            ],
        ];
        
//         return [
            
//             'name' => $this->name,
//             'salary' => $this->salary,
//             'checkin' => $this->attendanceAdjustments->first()->attendance->checkIN ?? null,
//             'date' => $this->attendanceAdjustments->first()->adjustment->date ?? null,
//             'checkOUT' => $this->attendanceAdjustments->first()->attendance->checkOUT ?? null,
//             'work_days' =>  $this->getWorkDaysAttribute($month, $year),
//             'absence_days' => $this->absence_days,
//             // 'bonuses' => $this->bonuses->map(function ($adjustment) {
//             //     return [
//             //         'id' => $adjustment->id,
//             //         'houres' => $adjustment->adjustment->houres ?? null,
//             //     ];
//             // }),
//             'total_bonus_hours' =>  $this->totalBonusHours($month, $year), 
//             'total_deduction_hours' => $this->totalDeductionsHours($month, $year),
//             //            'deductions' => $this->deductions->map(function ($adjustment) {
//             //     return [
//             //         'id' => $adjustment->id,
//             //         'houres' => $adjustment->adjustment->houres ?? null,
//             //     ];
//             // }),
//             // 'total_deduction_hours' => $this->totalDeductionsHours(),

// //             'department' => [
// //                 'name' => $this->department ? $this->department->name : null,
// //             ],
// // // 'salary_cal'=>$this->salaryPerMinute(),
// 'bonus_amount'=>$this->totalBounsAmount(),
// 'deductions_amount'=>$this->totalDeductionAmount(),
// // 'total_salary'=>$this->totalSalaryAmount(),
//         ];
    }
}