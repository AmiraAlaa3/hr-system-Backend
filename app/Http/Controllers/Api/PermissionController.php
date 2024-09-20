<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Http\Resources\PermissionResource;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions= Permission::all();
        return PermissionResource::collection($permissions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'page' => 'required|string',
            'add' => 'required|in:true,false',
            'delete' => 'required|in:true,false',
            'edit' => 'required|in:true,false',
            'view' => 'required|in:true,false',
        ]);

        $permission = Permission::create([
            'page' => $request->page,
            'add' => $request->add,
            'delete' => $request->delete,
            'edit' => $request->edit,
            'view' => $request->view,
        ]);

        $permission = Permission::create($request->only('page', 'add', 'delete', 'edit', 'view'));

        return new PermissionResource($permission);
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
        //
    }
}
