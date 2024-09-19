<?php
use App\Http\Controllers\Api\{
    DepartmentController,
    EmployeeController,
    HolidaysController,
    SalariesController,
    AttendanceController,
    DashboardController,
    UserController,
    GroupController,
    genral_settingController,
    LoginController
};
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication
Route::post('login', [LoginController::class, 'login']);
Route::middleware(['auth:sanctum'])->post('logout', [LoginController::class, 'logout']);

// Protected Routes
Route::middleware(['auth:sanctum'])->group(function () {

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index']);

    // Employees
    Route::middleware('check.permission:employee,view')->group(function () {
        Route::get('employees', [EmployeeController::class, 'index']);
        Route::get('employees/search', [EmployeeController::class, 'search']);
        Route::get('employees/{employee}', [EmployeeController::class, 'show']);
    });
    Route::middleware('check.permission:employee,add')->group(function () {
        Route::post('employees', [EmployeeController::class, 'store']);
    });
    Route::middleware('check.permission:employee,edit')->group(function () {
        Route::put('employees/{employee}', [EmployeeController::class, 'update']);
    });
    Route::middleware('check.permission:employee,delete')->group(function () {
        Route::delete('employees/{employee}', [EmployeeController::class, 'destroy']);
    });

    // Departments
    Route::middleware('check.permission:department,view')->group(function () {
        Route::get('departments', [DepartmentController::class, 'index']);
        Route::get('departments/search', [DepartmentController::class, 'search']);
        Route::get('departments/{department}', [DepartmentController::class, 'show']);
    });
    Route::middleware('check.permission:department,add')->group(function () {
        Route::post('departments', [DepartmentController::class, 'store']);
    });
    Route::middleware('check.permission:department,edit')->group(function () {
        Route::put('departments/{department}', [DepartmentController::class, 'update']);
    });
    Route::middleware('check.permission:department,delete')->group(function () {
        Route::delete('departments/{department}', [DepartmentController::class, 'destroy']);
    });

    // Salaries
    Route::middleware('check.permission:salary,view')->group(function () {
        Route::get('salaries', [SalariesController::class, 'index']);
        Route::get('salary/search', [SalariesController::class, 'search']);
        Route::get('salary/search-by-month-year', [SalariesController::class, 'calculateBonusDeduction']);
        Route::get('salaries/{salary}', [SalariesController::class, 'show']);
    });
    Route::middleware('check.permission:salary,add')->group(function () {
        Route::post('salaries', [SalariesController::class, 'store']);
    });

    // Attendances
    Route::middleware('check.permission:attendance,view')->group(function () {
        Route::get('attendances', [AttendanceController::class, 'index']);
        Route::get('attendances/search', [AttendanceController::class, 'search']);
        Route::get('attendances/filter', [AttendanceController::class, 'filterByDate']);
        Route::get('attendances/{attendance}', [AttendanceController::class, 'show']);
    });
    Route::middleware('check.permission:attendance,add')->group(function () {
        Route::post('attendances', [AttendanceController::class, 'store']);
        Route::post('attendances/ExcelImport', [AttendanceController::class, 'ExcelImport']);
    });
    Route::middleware('check.permission:attendance,edit')->group(function () {
        Route::put('attendances/{attendance}', [AttendanceController::class, 'update']);
    });
    Route::middleware('check.permission:attendance,delete')->group(function () {
        Route::delete('attendances/{attendance}', [AttendanceController::class, 'destroy']);
    });

    // Holidays
    Route::middleware('check.permission:holiday,view')->group(function () {
        Route::get('holidays', [HolidaysController::class, 'index']);
        Route::get('holidays/{holiday}', [HolidaysController::class, 'show']);
    });
    Route::middleware('check.permission:holiday,add')->group(function () {
        Route::post('holidays', [HolidaysController::class, 'store']);
    });
    Route::middleware('check.permission:holiday,edit')->group(function () {
        Route::put('holidays/{holiday}', [HolidaysController::class, 'update']);
    });
    Route::middleware('check.permission:holiday,delete')->group(function () {
        Route::delete('holidays/{holiday}', [HolidaysController::class, 'destroy']);
    });

    // General Settings
    Route::middleware('check.permission:settings,edit')->group(function () {
        Route::get('setting', [genral_settingController::class, 'index']);
        Route::put('setting/{id}', [genral_settingController::class,'update']);
    });

    // Users
    Route::middleware('check.permission:user,view')->group(function () {
        Route::get('users', [UserController::class, 'index']);
        Route::get('users/{user}', [UserController::class, 'show']);
    });
    Route::middleware('check.permission:user,add')->group(function () {
        Route::post('users', [UserController::class, 'store']);
    });
    Route::middleware('check.permission:user,edit')->group(function () {
        Route::put('users/{user}', [UserController::class, 'update']);
    });
    Route::middleware('check.permission:user,delete')->group(function () {
        Route::delete('users/{user}', [UserController::class, 'destroy']);
    });

    // Groups
    Route::middleware('check.permission:group,view')->group(function () {
        Route::get('groups', [GroupController::class, 'index']);
        Route::get('groups/{group}', [GroupController::class, 'show']);
    });
    Route::middleware('check.permission:group,add')->group(function () {
        Route::post('groups', [GroupController::class, 'store']);
    });
    Route::middleware('check.permission:group,delete')->group(function () {
        Route::delete('groups/{group}', [GroupController::class, 'destroy']);
    });
});
