<?php

use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\AttendanceCotroller;
use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\Api\LoginController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// attendance
Route::apiResource('attendances', AttendanceCotroller::class);

//Login
Route::post('login',[LoginController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function () {
    // employees
    Route::get('employees/search', [EmployeeController::class, 'search']);
    Route::apiResource('employees', EmployeeController::class);

    // departments
    Route::get('departments/search', [DepartmentController::class, 'search']);
    Route::apiResource('departments',DepartmentController::class);

    //Add all the routes Heeeerrrrrreeeeeeeeee pleeeeaaaasssss
});

