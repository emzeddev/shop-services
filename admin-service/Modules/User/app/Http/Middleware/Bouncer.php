<?php

namespace Modules\User\Http\Middleware;

use Illuminate\Support\Facades\Route;

class Bouncer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, \Closure $next, $guard = 'admin')
    {
        if (! auth()->guard($guard)->check()) {
            auth()->guard('admin')->logout();
            
            return response()->json([
                'message' => 'you should be login to your account',
                'error' => true,
                'data' => null
            ], 401);
        }

        /**
         * If user status is changed by admin. Then session should be
         * logged out.
         */
        if (! (bool) auth()->guard($guard)->user()->status) {
            auth()->guard($guard)->logout();

            return response()->json([
                'message' => 'you should be relogin to your account',
                'error' => true,
                'data' => null
            ], 401);
        }

        /**
         * If somehow the user deleted all permissions, then it should be
         * auto logged out and need to contact the administrator again.
         */
        if ($this->isPermissionsEmpty()) {
            auth()->guard('admin')->logout();

            return response()->json([
                'message' => '',
                'error' => true,
                'data' => null
            ], 403);
        }

        return $next($request);
    }

    /**
     * Check for user, if they have empty permissions or not except admin.
     *
     * @return bool
     */
    public function isPermissionsEmpty()
    {
        if (! $role = auth()->guard('admin')->user()->role) {
            return response()->json([
                'message' => 'This action is unauthorized.',
                'error' => true,
                'data' => null
            ], 401);
        }

        if ($role->permission_type === 'all') {
            return false;
        }

        if (
            $role->permission_type !== 'all'
            && empty($role->permissions)
        ) {
            return true;
        }

        // $this->checkIfAuthorized();

        return false;
    }

    /**
     * Check authorization.
     *
     * @return null
     */
    // public function checkIfAuthorized()
    // {
    //     $roles = acl()->getRoles();

    //     if (isset($roles[Route::currentRouteName()])) {
    //         bouncer()->allow($roles[Route::currentRouteName()]);
    //     }
    // }
}
