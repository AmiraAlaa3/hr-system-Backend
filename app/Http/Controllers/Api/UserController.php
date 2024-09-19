<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Http\Resources\UserResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['groups.permissions'])->get();
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'Full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'nullable|string|min:8',
            'group_id' => 'required|exists:groups,id',
        ]);

        if ($validatedData->fails()) {
            return response()->json($validatedData->errors(), 400);
        }

        $validatedData = $validatedData->validated();

        // Update the user details
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'Full_name' => $validatedData['Full_name'],
            'password' => Hash::make($validatedData['password']),
            'group_id' => $validatedData['group_id'],
        ]);


        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with(['groups.permissions'])->find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'Full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'group_id' => 'required|exists:groups,id',
        ]);

        if ($validatedData->fails()) {
            return response()->json($validatedData->errors(), 400);
        }

        $validatedData = $validatedData->validated();

        $user = User::findOrFail($id);

        // Update the user details
        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'Full_name' => $validatedData['Full_name'],
            'password' => $request->filled('password') ? Hash::make($validatedData['password']) : $user->password,
            'group_id' => $validatedData['group_id'],
        ]);


        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Delete the user
        $user->delete();

        // Return a success response
        return response()->json(['message' => 'User successfully deleted'], Response::HTTP_OK);
    }
}
