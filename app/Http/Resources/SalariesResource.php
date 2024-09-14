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
            'checkin' => $this->attendanceAdjustments->first()->attendance->checkIN ?? null,
            'date' => $this->attendanceAdjustments->first()->adjustment->date ?? null,
            'checkOUT' => $this->attendanceAdjustments->first()->attendance->checkOUT ?? null,
            'work_days' =>  $this->getWorkDaysAttribute($month, $year),  // Using the dynamic value or fallback
            'absence_days' => $this->getAbsenceDaysAttribute($month, $year),
            'total_bonus_hours' => $this->totalBonusHours($month, $year),
            'total_deduction_hours' => $this->totalDeductionsHours($month, $year),
            'bonus_amount' => $this->totalBounsAmount($month, $year),
            'deductions_amount' => $this->totalDeductionAmount($month, $year),
            'salary_cal'=>$this->salaryPerMinute(),
            'total_salary'=>$this->totalSalaryAmount($month , $year),
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