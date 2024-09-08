<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendnce;

class AttendanceCotroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       //return only employlee have attendances
        $attendances = Attendnce::with(['employee.department'])->get();
        return AttendanceResource::collection($attendances);
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
        $attendance = Attendnce::find($id);
        if (!$attendance) {
            return response()->json(['message' => 'attendance not found'], 404);
        }
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
        $attendance = Attendnce::find($id);
        if (!$attendance) {
            return response()->json(['message' => 'attendance not found'], 404);
        }
        $attendance->delete();
        return response()->json(['message' => 'attendance deleted successfully'], 200);
    }
}
