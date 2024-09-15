<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendnce;

use App\Models\Adjustment;
use App\Http\Resources\SalariesResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
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

        
        $employee = Employee::with(['attendances'])
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


    public function calculateBonusDeduction( Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'month' => 'integer|between:1,12', 
                    'year' => 'integer|min:2008',      
                ]);
                  $month = $request->input('month');
        $year = $request->input('year');  
        $employees = Employee::whereHas('attendances', function ($q) use ($month, $year) {
                            $q->whereMonth('date', $month)
                            ->whereYear('date', $year);
                        
                    })->get();
        //  $employee = Employee::findOrFail($employeeId);

         $employees->each(function ($employee) use ($month, $year) {
                    $employee->getWorkDaysAttribute($month, $year);
                    $employee->getAbsenceDaysAttribute($month, $year);
                    $employee->totalSalaryAmount($month, $year);
                    $employee->calculateMonthlyBonusDeduction($month, $year); 
                });
 
         return SalariesResource::collection($employees);

}


}