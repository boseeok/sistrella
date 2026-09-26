<?php

use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Hosting platforms terminate HTTPS at a proxy / load balancer; trust
        // its X-Forwarded-* headers so generated URLs use https.
        $middleware->trustProxies(at: '*');

        // Guests hitting the admin panel go to the staff login, not the shop login.
        $middleware->redirectGuestsTo(fn (Request $request) => $request->is('admin', 'admin/*')
            ? route('admin.login')
            : route('login'));

        $middleware->alias([
            'demo.readonly' => \App\Http\Middleware\DemoReadOnly::class,
            'admin'      => EnsureUserIsAdmin::class,
            'permission' => CheckPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
