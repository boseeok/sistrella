<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gate the admin panel: only authenticated, active staff (admin/manager
 * or any role carrying a dashboard.access permission) may enter.
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('admin.login');
        }

        if (! $user->is_active) {
            auth()->logout();

            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Your account has been deactivated.']);
        }

        if (! $user->isAdmin() && ! $user->hasPermission('dashboard.access')) {
            abort(403, 'You do not have access to the admin panel.');
        }

        return $next($request);
    }
}
