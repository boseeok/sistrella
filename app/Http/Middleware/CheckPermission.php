<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Route-level permission guard. Usage: ->middleware('permission:orders.view').
 * Multiple permissions may be passed; any one grants access.
 */
class CheckPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasAnyPermission($permissions)) {
            abort(403, 'You are not authorized to perform this action.');
        }

        return $next($request);
    }
}
