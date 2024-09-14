<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardResource;
use App\Models\Annual_Holidays;
use App\Models\Dashboard;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function countDashboard()
    {
        $totalEmployee = Employee::count();
        $totalUsers = User::count();
        $totalLeaves = Annual_Holidays::count();
        $nextLeave = Annual_Holidays::where('date', '>=', now())->first();

        dd($totalEmployee);
        return response()->json([
            'totalEmployee' => $totalEmployee,
            'totalUsers' => $totalUsers,
            'totalLeaves' => $totalLeaves,
            'nextLeave' => $nextLeave ? $nextLeave->date : null
        ], 200);
    }

}
