<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectSuperadminToAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->isSuperadmin() && ! $request->session()->has('readonly_impersonated_user_id')) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
