<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\EmployeeAttendanceAdjustment;
use App\Http\Resources\WeekendResource;
use App\Models\Annual_Holidays;
use Illuminate\Support\Facades\Validator;
use App\Models\Casual_Holidays;
use App\Models\User;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Weekend;

class WeekendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $weekend=weekend::all();

        return WeekendResource::collection($weekend);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validateDate = Validator::make($request->all(),[
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            // 'days' => 'required|string',
          ]);

          if($validateDate->fails()){
             return response()->json($validateDate->errors(), 400);
          }

          $weekend= weekend::create($validateDate->validated());
          return new WeekendResource($weekend);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $weekend = weekend::findOrFail($id);
        return new WeekendResource($weekend);
    }

    /**
     * Update the specified resource in storage.
     */
      

    public function update(Request $request, string $id)
    {
        $weekend = weekend::findOrfail($id);
        $validateDate = Validator::make($request->all(),[
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'days' => 'required|enum',
          ]);

          if($validateDate->fails()){
             return response()->json($validateDate->errors(), 400);
          }

          $weekend->update($validateDate->validated());
          return new WeekendResource($weekend);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $weekend = weekend::find($id);
        if (!$weekend) {
            return response()->json(['message' => 'weekend not found'], 404);
        }
    
        $weekend->delete();
    
        return response()->json(['message' => 'weekend deleted successfully']);
    }
}
