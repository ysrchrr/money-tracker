<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $user = Auth::user();
            $impersonatedMember = null;

            if ($user?->isSuperadmin() && session()->has('readonly_impersonated_user_id')) {
                $impersonatedMember = User::query()
                    ->where('role', 'member')
                    ->whereKey(session('readonly_impersonated_user_id'))
                    ->first();
            }

            $view->with('impersonatedMember', $impersonatedMember);
            $view->with('isReadonlyImpersonation', (bool) $impersonatedMember);
        });
    }
}
