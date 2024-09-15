<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Resources\HolidaysResource;
use App\Models\Annual_Holidays;
use Illuminate\Support\Facades\Validator;


class HolidaysController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $holidays = Annual_Holidays::all();

        return HolidaysResource::collection($holidays);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validateDate = Validator::make($request->all(),[
            'date' => 'required|date',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'from_date' => 'required|date',
            'to_date' => 'required|date',
            'numberOfDays' => 'required|integer',
          ]);

          if($validateDate->fails()){
             return response()->json($validateDate->errors(), 400);
          }

          $holidays= Annual_Holidays::create($validateDate->validated());
          return new HolidaysResource($holidays);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $holiday = Annual_Holidays::findOrFail($id);
        return new HolidaysResource($holiday);
    }

    /**
     * Update the specified resource in storage.
     */


    public function update(Request $request, string $id)
    {
        $holidays = Annual_Holidays::findOrfail($id);
        $validateDate = Validator::make($request->all(),[
            'date' => 'required|date',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'from_date' => 'required|date',
            'to_date' => 'required|date',
            'numberOfDays' => 'required|integer',
          ]);

          if($validateDate->fails()){
             return response()->json($validateDate->errors(), 400);
          }

          $holidays->update($validateDate->validated());
          return new HolidaysResource($holidays);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $holiday = Annual_Holidays::find($id);
        if (!$holiday) {
            return response()->json(['message' => 'holiday not found'], 404);
        }

        $holiday->delete();

        return response()->json(['message' => 'holiday deleted successfully']);
    }
}
