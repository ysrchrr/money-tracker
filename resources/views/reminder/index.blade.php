<x-app-layout>
    @php($readonly = $isReadonlyImpersonation ?? false)

    <x-slot name="header">
        <div class="space-y-2">
            <p class="text-xs font-black uppercase tracking-[0.22em]">Daily Notification</p>
            <h1 class="text-3xl font-black uppercase">Reminder</h1>
        </div>
    </x-slot>

    <div class="grid gap-6 xl:grid-cols-[0.95fr_1.05fr]">
        <section class="brutal-panel p-6">
            <p class="text-xs font-black uppercase tracking-[0.22em]">Fitur ini sedang dalam pengembangan, hehe</p>
            {{-- <h2 class="mt-2 text-3xl font-black uppercase">
                {{ $readonly ? 'Readonly Mode' : ($canManageReminder ? 'Gold Access Active' : 'Locked for Silver') }}
            </h2> --}}

            @if ($readonly)
                <div class="mt-6 border-[4px] border-current bg-[#FF6B6B] px-4 py-4 text-sm font-black uppercase">
                    Admin sedang melihat reminder member secara readonly
                </div>
            @elseif (!$canManageReminder)
                <div class="mt-6 border-[4px] border-current bg-[#FFD93D] px-4 py-4 text-sm font-black uppercase">
                    Upgrade member type ke gold untuk mengaktifkan daily WhatsApp reminder
                </div>
            @endif

            {{-- <form method="POST" action="{{ route('reminder.update') }}" class="mt-6 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="brutal-label" for="whatsapp_number">WhatsApp Number</label>
                    <input id="whatsapp_number" name="whatsapp_number" class="brutal-input" type="text" value="{{ old('whatsapp_number', $setting->whatsapp_number) }}" {{ $canManageReminder ? '' : 'disabled' }}>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="brutal-label" for="send_time">Send Time</label>
                        <input id="send_time" name="send_time" class="brutal-input" type="time" value="{{ old('send_time', substr($setting->send_time, 0, 5)) }}" {{ $canManageReminder ? '' : 'disabled' }}>
                    </div>

                    <label class="flex items-center gap-3 border-[4px] border-current bg-white px-4 py-3">
                        <input type="checkbox" name="is_enabled" value="1" class="h-5 w-5 border-[3px] border-current accent-black" @checked(old('is_enabled', $setting->is_enabled)) {{ $canManageReminder ? '' : 'disabled' }}>
                        <span class="text-sm font-black uppercase tracking-[0.18em]">Enable Reminder</span>
                    </label>
                </div>

                <div>
                    <label class="brutal-label" for="message_template">Message Template</label>
                    <textarea id="message_template" name="message_template" rows="5" class="brutal-textarea" {{ $canManageReminder ? '' : 'disabled' }}>{{ old('message_template', $setting->message_template) }}</textarea>
                </div>

                @if ($canManageReminder)
                    <button type="submit" class="brutal-btn">Save Reminder</button>
                @endif
            </form> --}}
        </section>

        {{-- <section class="space-y-6">
            <div class="brutal-panel-violet p-6">
                <p class="text-xs font-black uppercase tracking-[0.22em]">Current Status</p>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div class="border-[4px] border-current bg-white p-4">
                        <p class="text-xs font-black uppercase tracking-[0.2em]">Enabled</p>
                        <p class="mt-2 text-2xl font-black uppercase">{{ $setting->is_enabled ? 'Yes' : 'No' }}</p>
                    </div>
                    <div class="border-[4px] border-current bg-[#FFD93D] p-4">
                        <p class="text-xs font-black uppercase tracking-[0.2em]">Send Time</p>
                        <p class="mt-2 text-2xl font-black uppercase">{{ substr($setting->send_time, 0, 5) }}</p>
                    </div>
                </div>
            </div>

            <div class="brutal-panel p-6">
                <p class="text-xs font-black uppercase tracking-[0.22em]">Recent Logs</p>
                <div class="mt-5 space-y-3">
                    @forelse ($recentLogs as $log)
                        <div class="border-[4px] border-current bg-white p-4">
                            <p class="text-sm font-black uppercase">{{ $log->status }} / {{ $log->trigger_source }}</p>
                            <p class="mt-1 text-sm font-bold">{{ $log->whatsapp_number }}</p>
                            <p class="mt-2 text-sm">{{ $log->message }}</p>
                        </div>
                    @empty
                        <div class="border-[4px] border-current bg-white px-4 py-4 text-sm font-black uppercase">
                            Belum ada log pengiriman.
                        </div>
                    @endforelse
                </div>
            </div>
        </section> --}}
    </div>
</x-app-layout>
