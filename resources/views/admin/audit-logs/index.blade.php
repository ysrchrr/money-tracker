<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <p class="text-xs font-black uppercase tracking-[0.22em]">Admin Trace</p>
            <h1 class="text-3xl font-black uppercase">Audit Log</h1>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="brutal-panel-violet p-6">
            <p class="text-xs font-black uppercase tracking-[0.22em]">Filters</p>
            <form method="GET" class="mt-5 grid gap-4 md:grid-cols-[260px_minmax(0,1fr)_auto]">
                <select name="action" class="brutal-select">
                    <option value="">All Actions</option>
                    @foreach ($actions as $action)
                        <option value="{{ $action }}" @selected(($filters['action'] ?? '') === $action)>{{ $action }}</option>
                    @endforeach
                </select>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="brutal-input" placeholder="Cari admin/member">
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="brutal-btn brutal-btn-secondary">Apply</button>
                    <a href="{{ route('admin.audit-logs.index') }}" class="brutal-btn brutal-btn-ghost">Reset</a>
                </div>
            </form>
        </section>

        <section class="brutal-panel p-6">
            <div class="mb-5">
                <p class="text-xs font-black uppercase tracking-[0.22em]">Latest 200</p>
                <h2 class="mt-2 text-3xl font-black uppercase">Admin Activity</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="brutal-ledger min-w-full" data-datatable="audit-logs" data-mobile-stack="true">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Action</th>
                            <th>Admin</th>
                            <th>Target</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($auditLogs as $log)
                            <tr>
                                <td class="text-sm font-bold uppercase" data-label="Time" data-order="{{ $log->created_at->format('Y-m-d H:i:s') }}">{{ $log->created_at->format('d M Y H:i') }}</td>
                                <td class="text-sm font-black uppercase" data-label="Action">{{ $log->action }}</td>
                                <td data-label="Admin">
                                    <p class="text-sm font-black uppercase">{{ $log->admin?->name ?? '-' }}</p>
                                    <p class="text-xs font-bold">{{ $log->admin?->email ?? '-' }}</p>
                                </td>
                                <td data-label="Target">
                                    <p class="text-sm font-black uppercase">{{ $log->targetUser?->name ?? '-' }}</p>
                                    <p class="text-xs font-bold">{{ $log->targetUser?->email ?? ($log->context['target_email'] ?? '-') }}</p>
                                </td>
                                <td class="text-sm font-bold" data-label="IP">{{ $log->ip_address ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
