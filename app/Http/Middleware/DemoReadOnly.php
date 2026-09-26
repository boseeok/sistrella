<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * In the public demo, lets visitors browse every admin screen but blocks any
 * change (create / update / delete / upload), so the showcase can't be
 * defaced between resets. Inactive unless DEMO_MODE is on.
 */
class DemoReadOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (config('crochet.demo.enabled') && config('crochet.demo.admin_read_only') && ! $request->isMethodSafe()) {
            $message = 'This is a read-only demo — changes in the admin panel are disabled.';

            return $request->expectsJson()
                ? response()->json(['message' => $message], 403)
                : back()->with('error', $message);
        }

        return $next($request);
    }
}
