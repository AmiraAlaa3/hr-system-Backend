<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::all();
        return DepartmentResource::collection($departments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $validateDate = Validator::make($request->all(),[
         'name' => 'required|string|max:255',
       ]);

       if($validateDate->fails()){
          return response()->json($validateDate->errors(), 400);
       }

       $department = Department::create($validateDate->validated());
       return new DepartmentResource($department);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $department = Department::findOrfail($id);
        return new DepartmentResource($department);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $department = Department::findOrfail($id);
        $validateDate = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
          ]);

          if($validateDate->fails()){
             return response()->json($validateDate->errors(), 400);
          }

          $department->update($validateDate->validated());
          return new DepartmentResource($department);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::find($id);
        if (!$department) {
            return response()->json(['message' => 'Department not found'], 404);
        }
        $department->delete();
        return response()->json(['message' => 'Department deleted successfully'], 200);
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
        $department = Department::where('name', 'LIKE', '%' . $name . '%')->get();

        if ($department->isEmpty()) {
            return response()->json(['message' => 'Department not found'], 404);
        }

        return DepartmentResource::collection($department);
    }
}
