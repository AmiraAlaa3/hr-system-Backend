<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeAttendanceAdjustment;
use App\Http\Resources\AttendanceResource;
use App\Http\Resources\EmployeeAttendanceResource;
use App\Models\Annual_Holidays;
use App\Models\Casual_Holidays;
use App\Models\User;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Weekend;
use App\Models\Attendnce;

class AttendanceCotroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::with(['department','attendances'])->get();
        // $employees = Employee::all();

        return EmployeeAttendanceResource::collection($employees);
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'checkIN' => 'required|date_format:H:i:s',
            'checkOUT' => 'required|date_format:H:i:s|after:attendance_time',
            'date' => 'required|date',
        ]);

        // $employee = Employee::findOrFail($request->employee_id);
        // $department_id = $employee->department_id;
        $attendance = Attendnce::create([
            'employee_id' => $request->employee_id,
            'checkIN' => $request->checkIN,
            'checkOUT' => $request->checkOUT,
            'date' => $request->date,
        ]);
    
        return new AttendanceResource($attendance);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //

        $attendance = Attendnce::find($id);

        return new AttendanceResource($attendance);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'checkIN' => 'required|date_format:H:i:s',
            'checkOUT' => 'required|date_format:H:i:s|after:attendance_time',
            'date' => 'required|date',
        ]);

        $attendance = Attendnce::findOrFail($id);

        $attendance->update([
            'employee_id' => $request->employee_id,
            'checkIN' => $request->checkIN,
            'checkOUT' => $request->checkOUT,
            'date' => $request->date,
        ]);
        return new AttendanceResource($attendance);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $attendance = Attendnce::find($id);

        $attendance->delete();
    }

    // search by name of employee and department name

    public function search (request $request){

        $validator = Validator::make($request->all(), [
            'employee_name' => 'nullable|string|max:255',
            'department_name' => 'nullable|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        $employeeName = $request->input('employee_name');
        $departmentName = $request->input('department_name');
    
        $query = Attendnce::query();
    
    
        if ($employeeName) {
            $query->whereHas('employee', function($q) use ($employeeName) {
                $q->where('name', 'LIKE', '%' . $employeeName . '%');
            });
        }
    
        if ($departmentName) {
            $query->whereHas('employee.department', function($q) use ($departmentName) {
                $q->where('name', 'LIKE', '%' . $departmentName . '%');
            });
        }
    
        $attendances = $query->get();
    
        if ($attendances->isEmpty()) {
            return response()->json(['message' => 'Attendance not found'], 404);
        }
    
        return AttendanceResource::collection($attendances);
    }

    // filter attendance by date

    public function filterByDate (request $request){
        $request->validate([
            "start_date"=> 'required|date|before_or_equal:end_date',
            "end_date"=>'required|date|after_or_equal:start_date'
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if(!$startDate || !$endDate){
            return response()->json(['message' => 'please enter correct date']);
        }

        $attendance = Attendnce::whereBetween('date',[$startDate,$endDate])->get();

        return AttendanceResource::collection($attendance);
    }
}
