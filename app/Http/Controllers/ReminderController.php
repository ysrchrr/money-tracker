<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateReminderSettingRequest;
use App\Models\ReminderSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReminderController extends Controller
{
    public function index(Request $request): View
    {
        $activeMember = $this->activeMember($request);

        $setting = $activeMember->reminderSetting()->firstOrCreate(
            [],
            [
                'whatsapp_number' => null,
                'is_enabled' => false,
                'send_time' => '08:00:00',
                'message_template' => 'Jangan lupa catat pengeluaran hari ini.',
            ],
        );

        return view('reminder.index', [
            'setting' => $setting,
            'canManageReminder' => ! $this->isReadonlyImpersonation($request) && ($activeMember->isGoldMember() || $request->user()->isSuperadmin()),
            'recentLogs' => $setting->logs()->latest('sent_at')->limit(10)->get(),
            'activeMember' => $activeMember,
        ]);
    }

    public function update(UpdateReminderSettingRequest $request): RedirectResponse
    {
        if (! $request->user()->isGoldMember() && ! $request->user()->isSuperadmin()) {
            return back()->withErrors(['reminder' => 'Reminder hanya tersedia untuk member gold.']);
        }

        $setting = ReminderSetting::firstOrCreate(
            ['user_id' => $request->user()->id],
            ['message_template' => 'Jangan lupa catat pengeluaran hari ini.'],
        );

        $setting->update([
            ...$request->validated(),
            'is_enabled' => $request->boolean('is_enabled'),
        ]);

        return back()->with('status', 'Reminder berhasil disimpan.');
    }
}
