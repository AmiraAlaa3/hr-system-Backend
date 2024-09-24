<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission,$action)
    {
        $user = $request->user();
        if ($user && $user->groups) {
            $permissions = $user->groups->permissions;

            $permissionObject = $permissions->firstWhere('page', $permission);

            if ($permissionObject && $permissionObject->$action === 'true') {
                return $next($request);
        }

        return response()->json(['message' => 'User does not have access to perform this action.'], 403);
    }
}
}
