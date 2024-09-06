<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\Permission;
use App\Models\Employee;




class UserController extends Controller
{
    public function index()
    {
    //    $employees = Employee::with('department')->get();
        // $employees = Employee::with('weekend')->get();
        // $employees = Employee::with('casual_holidays')->get();
        $employees = User::with(['employees'])->get();

        return $employees;
    }
}