<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * Checks whether the authenticated user's role has the given permission key.
     * Uses the existing can() helper (defined in app/Helpers/helpers.php) which
     * already handles Super Admin bypass and parent/child permission lookups.
     *
     * Usage in routes:  ->middleware('permission:permission_key')
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permissionKey  The permission key to check (matches permissions.key in DB)
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permissionKey)
    {
        // If user is not authenticated, let the 'auth' middleware handle it
        if (!auth()->check()) {
            return $next($request);
        }

        $permissions = explode('|', $permissionKey);
        $hasPermission = false;
        foreach ($permissions as $p) {
            if (can(trim($p))) {
                $hasPermission = true;
                break;
            }
        }

        // Check permission using the can() helper
        if (!$hasPermission) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to access this resource.',
                ], 403);
            }

            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
