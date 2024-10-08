<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Permission;
use App\Http\Resources\GroupResource;



class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = Group::all();
        return GroupResource::collection($groups);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|string|max:255|unique:groups,name',
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'exists:permissions,id'
        ]);
    
        $group = Group::create([
            'name' => $validateData['name']
        ]);
    
        $group->permissions()->sync($validateData['permission_ids']);
    
        return new GroupResource($group);
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
       $group = Group::find($id);
       if (!$group) {
           return response()->json(['message' => 'group not found'], 404);
       }
       $group->delete();
        return response()->json(['message' => 'Group deleted successfully.']);
    }
    public function assignPermissions(Request $request, $groupId)
    {
        // Fetch the group by ID
        $group = Group::findOrFail($groupId);

        // Get permissions from the request
        $permissions = Permission::whereIn('page', $request->input('permissions'))->get();

        // Assign permissions to the group
        $group->permissions()->sync($permissions);

        return response()->json(['message' => 'Permissions assigned successfully.']);
    }
}
