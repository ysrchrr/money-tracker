<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReadonlyImpersonation
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->has('readonly_impersonated_user_id') && ! $request->isMethodSafe()) {
            abort(403, 'Readonly impersonation aktif.');
        }

        return $next($request);
    }
}
