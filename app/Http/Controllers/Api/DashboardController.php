<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Annual_Holidays;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $totalEmployee = Employee::count();
            $totalUsers = User::count();
            $totalLeaves = Annual_Holidays::count();
            $nextLeave = Annual_Holidays::where('date', '>=', now())->first();
            $emplyeeAndDepartment = Department::withCount('employees')->get();

            return response()->json([
                'totalEmployee' => $totalEmployee,
                'totalUsers' => $totalUsers,
                'totalLeaves' => $totalLeaves,
                'nextLeave' => $nextLeave ? [
                    'title' => $nextLeave->title,
                    'date' => $nextLeave->date,
                ] : null,
                'departments' => $emplyeeAndDepartment->pluck('name'),
                'employeeCounts' => $emplyeeAndDepartment->pluck('employees_count'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
