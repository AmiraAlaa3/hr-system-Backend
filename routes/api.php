<?php

use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\HolidaysController;
use App\Http\Controllers\Api\WeekendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// employees
Route::get('employees/search', [EmployeeController::class, 'search']);
Route::apiResource('employees', EmployeeController::class);
// departments
Route::get('departments/search', [DepartmentController::class, 'search']);
Route::apiResource('departments',DepartmentController::class);

//holidays
// Route::get('holidays/index',[HolidaysController::class, 'index']);
// Route::apiResource('holidays',HolidaysController::class);


Route::apiResource('holidays', HolidaysController::class);




Route::apiResource('weekends',WeekendController::class);



// Route::apiResource('holidays', HolidayController::class);


// Route::get('/holidays/{id}', [HolidaysController::class, 'show']);
// Route::put('/holidays/{id}', [HolidaysController::class, 'update']);
// Route::delete('/holidays/{id}', [HolidaysController::class, 'destroy']);