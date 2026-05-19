<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ImpersonationController extends Controller
{
    public function store(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role === 'member', 404);

        $request->session()->put('readonly_impersonated_user_id', $user->id);

        $this->log($request, 'impersonation.start', $user);

        return redirect()
            ->route('dashboard')
            ->with('status', 'Readonly impersonation aktif: '.$user->email);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $target = null;
        $targetId = $request->session()->get('readonly_impersonated_user_id');

        if ($targetId) {
            $target = User::query()->find($targetId);
        }

        $request->session()->forget('readonly_impersonated_user_id');

        $this->log($request, 'impersonation.stop', $target);

        return redirect()
            ->route('admin.members.index')
            ->with('status', 'Readonly impersonation dihentikan.');
    }

    private function log(Request $request, string $action, ?User $target): void
    {
        AdminAuditLog::query()->create([
            'admin_id' => $request->user()->id,
            'target_user_id' => $target?->id,
            'action' => $action,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
            'context' => [
                'target_email' => $target?->email,
            ],
        ]);
    }
}
