<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\SalariesResource;
use App\Models\Employee;
use Carbon\Carbon;
use App\Models\EmployeeAttendanceAdjustment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employee = Employee::all();
        return EmployeeResource::collection($employee);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees',
            'birthdate' => [
                'required',
                'date',
                'before:today',
                function ($attribute, $value, $fail) {
                    $age = Carbon::parse($value)->age;
                    if ($age < 20) {
                        $fail('The employee must be at least 20 years old.');
                    }
                },
            ],
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:11',
            'hire_date' => 'required|date',
           'ssn' => [
                'required',
                'numeric',
                'unique:employees',
                function ($attribute, $value, $fail) use ($request) {
                    if (!$this->validateNationalIdWithBirthdate($value, $request->birthdate)) {
                        $fail('The national ID does not match the birthdate.');
                    }
                },
            ],
            'gender' => 'required|string|max:10',
            'nationality' => 'required|string|max:50',
            'position' => 'required|string',
            'Marital_status' => 'required|string|max:10',
            'salary' => 'required|numeric',
            'check_in_time' => 'required',
            'check_out_time' => 'required',
            'department_id' => 'required|exists:departments,id',
        ]);

        if ($validatedData->fails()) {
            return response()->json(['error' => $validatedData->errors()], 400);
        }
        $validatedData = $validatedData->validated();
        $employee = Employee::create($validatedData);
        return new EmployeeResource($employee);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $employee = Employee::findOrFail($id);
       return new EmployeeResource($employee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('employees')->ignore($employee->id),
            ],
            'birthdate' => [
                'required',
                'date',
                'before:today',
                function ($attribute, $value, $fail) {
                    $age = Carbon::parse($value)->age;
                    if ($age < 20) {
                        $fail('The employee must be at least 20 years old.');
                    }
                },
            ],
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:11',
            'hire_date' => 'required|date',
            'ssn' => [
                'required',
                'numeric',
                Rule::unique('employees')->ignore($employee->id),
                function ($attribute, $value, $fail) use ($request) {
                    if (!$this->validateNationalIdWithBirthdate($value, $request->birthdate)) {
                        $fail('The national ID does not match the birthdate.');
                    }
                },
            ],
            'gender' => 'required|string|max:10',
            'nationality' => 'required|string|max:50',
            'position' => 'required|string',
            'Marital_status' => 'required|string|max:10',
            'salary' => 'required|numeric',
            'check_in_time' => 'required',
            'check_out_time' => 'required',
            'department_id' => 'required|exists:departments,id',
        ]);

        if ($validatedData->fails()) {
            return response()->json(['error' => $validatedData->errors()], 400);
        }

        $validatedData = $validatedData->validated();
        $employee->update($validatedData);

        return new EmployeeResource($employee);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(['message' => 'employee not found'], 404);
        }

        $employee->delete();

        return response()->json(['message' => 'employee deleted successfully']);
    }

    //
    private function validateNationalIdWithBirthdate($nationalId, $birthdate)
    {
        $year = substr($nationalId, 1, 2);
        $month = substr($nationalId, 3, 2);
        $day = substr($nationalId, 5, 2);
        $century = substr($nationalId, 0, 1);

        if ($century == 2) {
            $year = '19' . $year;
        } elseif ($century == 3) {
            $year = '20' . $year;
        }

        $extractedDate = Carbon::createFromFormat('Y-m-d', $year . '-' . $month . '-' . $day)->format('Y-m-d');
        $inputDate = Carbon::createFromFormat('Y-m-d', $birthdate)->format('Y-m-d');

        return $extractedDate === $inputDate;
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

        return EmployeeResource::collection($employee);
    }

}