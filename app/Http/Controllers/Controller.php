<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

abstract class Controller
{
    protected function activeMember(Request $request): User
    {
        $user = $request->user();
        $impersonatedUserId = $request->session()->get('readonly_impersonated_user_id');

        if ($user?->isSuperadmin() && $impersonatedUserId) {
            return User::query()
                ->where('role', 'member')
                ->whereKey($impersonatedUserId)
                ->firstOrFail();
        }

        return $user;
    }

    protected function isReadonlyImpersonation(Request $request): bool
    {
        return $request->user()?->isSuperadmin()
            && $request->session()->has('readonly_impersonated_user_id');
    }
}
