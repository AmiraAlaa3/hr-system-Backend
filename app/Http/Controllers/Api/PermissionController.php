<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Http\Resources\PermissionResource;


class PermissionController extends Controller
{
    /**
     * Store a newly created permission in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
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
     * Display a listing of the permissions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
//
    }

    /**
     * Show a specific permission.
     *
     * @param \App\Models\Permission $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Permission $permission)
    {
        return new PermissionResource($permission);
    }

    /**
     * Update the specified permission.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Permission $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Permission $permission)
    {
//
    }

    /**
     * Remove the specified permission from storage.
     *
     * @param \App\Models\Permission $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Permission $permission)
    {
    //
    }
}