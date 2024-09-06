<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeAttendanceAdjustment;
use App\Models\Annual_Holidays;
use App\Models\Casual_Holidays;
use App\Models\User;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Weekend;



class EmployeeController extends Controller
{
    public function index()
    {
    //    $employees = Employee::with('department')->get();
        // $employees = Employee::with('weekend')->get();
        // $employees = Employee::with('casual_holidays')->get();
        // $employees = Employee::with(['attendanceAdjustments.attendance'])->get();

        // return $employees;
    }
    // public function index()
    // {
    //     $employees = Employee::all();

    //     return $employees;
    // }
}
