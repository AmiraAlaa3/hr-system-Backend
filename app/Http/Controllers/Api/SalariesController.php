<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Adjustment;
use App\Http\Resources\SalariesResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class SalariesController extends Controller

{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employee = Employee::all();

    
        return SalariesResource::collection($employee);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        
        $employee = Employee::with(['attendanceAdjustments.attendance','attendanceAdjustments.adjustment'])
        ->findOrFail($id);
     
        return new SalariesResource($employee);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }
// public function update(Request $request, $id)
// {
//     // Validate the incoming request
//     $validator = Validator::make($request->all(), [
//         'work_days' => 'integer|min:0',
//         'salary' => 'required|numeric',
//         'bonuses.*.houres' => 'required|nullable|integer',
//     ]);

//     if ($validator->fails()) {
//         return response()->json($validator->errors(), 400);
//     }

//     // Find the employee with related models
//     $employee = Employee::with(['attendanceAdjustments.attendance', 'attendanceAdjustments.adjustment'])
//         ->findOrFail($id);


//     $validated = $validator->validated();
//     $employee->fill($validated);

//     if ($request->has('work_days')) {
//         $workDays = $request->input('work_days');
//         $employee->updateWorkDays($workDays);
//     }
//     if ($request->has('bonuses.*.houres')) {
//         $totalhours=  $request->input('bonuses.*.houres') ;
//         $employee->newtotalBonusHours($totalhours);
//     }

//     $employee->save();

//     // Return the updated employee data with work_days
//     return new SalariesResource($employee);
// }




    // public function hamed($num)
    // {
    //     // Simply return the passed value or handle other logic if necessary
    //     return $num;
    // }




 

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $name = $request->input('name');
        $employee = Employee::where('name', 'LIKE', '%' . $name . '%')->get();
        if ($employee->isEmpty()) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        return SalariesResource::collection($employee);
    }

    public function searchByMonthAndYear(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'month' => 'integer|between:1,12', 
            'year' => 'integer|min:2008',      
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        $month = $request->input('month');
        $year = $request->input('year');
    
        if (!$month && !$year) {
            return response()->json(['message' => 'Please provide at least a month or a year'], 400);
        }
    else if($year){
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        $year = $request->input('year');
    
        $employeesData = collect();
    
        for ($month = 1; $month <= 12; $month++) {
    
            $employees = Employee::whereHas('attendanceAdjustments', function ($query) use ($month, $year) {
                $query->whereHas('attendance', function ($q) use ($month, $year) {
                    $q->whereMonth('date', $month)
                    ->whereYear('date', $year);
                });
            })->get();
    
            $employees->each(function ($employee) use ($month, $year, &$employeesData) {
                $workDays = $employee->getWorkDaysAttribute($month, $year);
                $bonusHours = $employee->totalBonusHours($month, $year);
                $absenceDays = $employee->getAbsenceDaysAttribute($month, $year);
                $deductionHours = $employee->totalDeductionsHours($month, $year);
                $totalBonusAmount = $employee->totalBounsAmount($month, $year);
                $totalDeductionAmount = $employee->totalDeductionAmount($month, $year);
                $totalSalaryAmount = $employee->totalSalaryAmount($month, $year);
    
                $employeesData->push([
                    'name' => $employee->name,
                    'month' => $month,
                    'year' => $year,
                    'work_days' => $workDays,
                    'bonus_hours' => $bonusHours,
                    'absence_days' => $absenceDays,
                    'deduction_hours' => $deductionHours,
                    'total_bonus_amount' => $totalBonusAmount,
                    'total_deduction_amount' => $totalDeductionAmount,
                    'total_salary_amount' => $totalSalaryAmount,
                ]);
            });
        }
    
        if ($employeesData->isEmpty()) {
            return response()->json(['message' => 'No salaries found for the specified year'], 404);
        }
    
        return response()->json($employeesData);
    
       
    }
        $employees = Employee::whereHas('attendanceAdjustments', function ($query) use ($month, $year) {
            $query->whereHas('attendance', function ($q) use ($month, $year) {
                if ($month) {
                    $q->whereMonth('date', $month);
                }
                if ($year) {
                    $q->whereYear('date', $year);
                }
            });
        })->get();
    
        if ($employees->isEmpty()) {
            return response()->json(['message' => 'No employees found for the specified month and/or year'], 404);
        }
    
        $employees->each(function ($employee) use ($month, $year) {
            $employee->getWorkDaysAttribute($month, $year);
            $employee->totalBonusHours($month, $year);
            $employee->getAbsenceDaysAttribute($month, $year);
            $employee->totalDeductionsHours($month, $year);
            $employee->totalBounsAmount($month, $year);
            $employee->totalDeductionAmount($month, $year);
            $employee->totalSalaryAmount($month, $year);
        });
    
        return SalariesResource::collection($employees);
    }
    




}