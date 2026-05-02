<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifySession
{
    /**
     * Handle role and status verification for incoming requests.
     *
     * @param Request $request The incoming HTTP request.
     * @param Closure $next The next middleware or request handler.
     * @param mixed ...$roles The roles that are allowed to access the resource.
     * @return mixed The response from the next middleware or request handler, or a JSON response with a 403 status code if access is forbidden.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (
            $user &&
            !empty($roles) &&
            in_array($user->role, $roles) &&
            $user->status === 'active'
        ) {
            return $next($request);
        }

        return response()->json(['message' => 'Forbidden'], 403);
    }
}
