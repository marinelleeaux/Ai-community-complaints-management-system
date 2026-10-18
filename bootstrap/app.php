<?php

use App\Http\Middleware\EnsureAccountActive;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureApprovedResident;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'admin' => EnsureAdmin::class,
            'resident.approved' => EnsureApprovedResident::class,
            'account.active' => EnsureAccountActive::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'login',
            'logout',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            return redirect()->route('login', ['role' => 'admin', 'switch' => 1])
                ->with('error', 'Your session expired. Please sign in again.');
        });
    })->create();
