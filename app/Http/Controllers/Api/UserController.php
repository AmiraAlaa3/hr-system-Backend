<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
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
    // Validate a single group_id
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'group_id' => 'required|exists:groups,id',  // Validate a single group_id
    ]);

    // Create the user
    $user = User::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'password' => Hash::make($validatedData['password']),
    ]);

    // Attach the single group
    $user->groups()->sync([$validatedData['group_id']]);  // Sync single group as an array

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
        // Validate a single group_id
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,  // Ensure unique email, except for current user
            'password' => 'nullable|string|min:8',  // Password is optional for updates
            'group_id' => 'required|exists:groups,id',  // Validate a single group_id
        ]);
    
        $user = User::findOrFail($id);
    
        // Update the user details
        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $request->filled('password') ? Hash::make($validatedData['password']) : $user->password,
        ]);
    
        // Attach the single group
        $user->groups()->sync([$validatedData['group_id']]);  // Sync single group as an array
    
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
        return response()->json([  'message' => 'User successfully deleted' ], Response::HTTP_OK);
    }
}
