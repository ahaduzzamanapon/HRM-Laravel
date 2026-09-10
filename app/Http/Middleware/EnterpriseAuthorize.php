<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\AuthorizationEngine;
use App\Services\AuditService;

class EnterpriseAuthorize
{
    /**
     * Handle an incoming request with enterprise authorization checks and audit logging.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission = null)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // 1. Permission key validation if passed to middleware
        if ($permission) {
            $permissions = explode('|', $permission);
            $hasPermission = false;
            foreach ($permissions as $p) {
                if (AuthorizationEngine::can(trim($p), $user)) {
                    $hasPermission = true;
                    break;
                }
            }

            if (!$hasPermission) {
                AuditService::log(
                    $request->segment(1) ?? 'System',
                    'UNAUTHORIZED_ACCESS_DENIED',
                    $permission
                );

                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Unauthorized access denied.'], 403);
                }
                abort(403, 'You do not have permission to access this module or resource.');
            }
        }

        // 3. Audit log allowed action
        $routeMethod = $request->method();
        if (in_array($routeMethod, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            AuditService::log(
                $request->segment(1) ?? 'System',
                strtolower($routeMethod),
                $permission
            );
        }

        return $next($request);
    }
}
