<?php

namespace App\Http\Controllers\Api;
use App\Imports\AttendancesImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeAttendanceAdjustment;
use App\Http\Resources\AttendanceResource;
use App\Models\Annual_Holidays;
use App\Models\Casual_Holidays;
use App\Models\User;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Weekend;
use App\Models\Attendnce;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       //return only employee have attendances
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
            'checkIN' => ['required', 'regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/'],
            'checkOUT' => ['required', 'regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/', 'after:checkIN'],
            'date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = DB::table('attendnces')
                        ->where('employee_id', $request->employee_id)
                        ->where('date', $value)
                        ->exists();

                    if ($exists) {
                        $fail('Attendance for this date already exists for this employee.');
                    }
                },
            ],
        ]);

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
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'checkIN' => ['required', 'regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/'],
            'checkOUT' => ['required', 'regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/', 'after:checkIN'],
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

    // import file excel

    public function ExcelImport(Request $request){
          $request->validate([
            'import_file'=>[
                'required',
                'file',
            ],
          ]);
          Excel::import(new AttendancesImport, $request->file('import_file'));

        //   $attendance = Attendnce::all();
          return response()->json(['message' => 'File imported successfully.']);
    }
}
