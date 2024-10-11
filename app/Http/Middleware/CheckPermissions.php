<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class CheckPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permissions): Response
    {
        $user = Auth::user();

        // Check if the user has 'full-access' permission
        if ($user->hasPermissionTo('full-access')) {
            return $next($request);
        }

        // Check for other specified permissions
        foreach (explode('|', $permissions) as $permission) {
            if ($user->hasPermissionTo($permission)) {
                return $next($request);
            }
        }

        // If no permissions match, deny access
        return response()->json([
            'status' => 'error',
            'statusCode' => 403,
            'message' => "You don't have permission to access this resource.",
        ], 403);
    }
}
