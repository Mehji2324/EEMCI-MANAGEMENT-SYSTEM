<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Usage in routes:
     *   ->middleware('role:admin')
     *   ->middleware('role:admin,teacher')
     *
     * @param string $roles Comma-separated list of allowed role names
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $userRole = $user->role?->name;

        if (empty($roles) || in_array($userRole, $roles)) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Forbidden. You do not have permission to access this resource.',
        ], 403);
    }
}
