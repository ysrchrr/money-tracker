<?php

use App\Http\Middleware\EnsureSuperadmin;
use App\Http\Middleware\ReadonlyImpersonation;
use App\Http\Middleware\RedirectSuperadminToAdmin;
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
        $middleware->alias([
            'member.context' => RedirectSuperadminToAdmin::class,
            'readonly.impersonation' => ReadonlyImpersonation::class,
            'superadmin' => EnsureSuperadmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
