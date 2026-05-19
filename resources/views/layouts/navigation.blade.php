@php
    $currentUser = Auth::user();
    $readonlyMember = $impersonatedMember ?? null;
    $isAdminNavigation = $currentUser?->isSuperadmin() && !($isReadonlyImpersonation ?? false);
    $homeRoute = $isAdminNavigation ? route('admin.dashboard') : route('dashboard');
    $navItems = $isAdminNavigation
        ? [
            [
                'label' => 'Dashboard',
                'url' => route('admin.dashboard'),
                'active' => request()->routeIs('admin.dashboard'),
                'icon' => 'fa-solid fa-chart-column',
            ],
            [
                'label' => 'Members',
                'url' => route('admin.members.index'),
                'active' => request()->routeIs('admin.members.*'),
                'icon' => 'fa-solid fa-users',
            ],
            [
                'label' => 'Transactions',
                'url' => route('admin.transactions.index'),
                'active' => request()->routeIs('admin.transactions.*'),
                'icon' => 'fa-solid fa-receipt',
            ],
            [
                'label' => 'Reports',
                'url' => route('admin.reports.index'),
                'active' => request()->routeIs('admin.reports.*'),
                'icon' => 'fa-solid fa-file-lines',
            ],
            [
                'label' => 'Audit Log',
                'url' => route('admin.audit-logs.index'),
                'active' => request()->routeIs('admin.audit-logs.*'),
                'icon' => 'fa-solid fa-clock-rotate-left',
            ],
        ]
        : [
            [
                'label' => 'Dashboard',
                'url' => route('dashboard'),
                'active' => request()->routeIs('dashboard'),
                'icon' => 'fa-solid fa-chart-pie',
            ],
            [
                'label' => 'Cash Flow',
                'url' => route('cash-flows.index'),
                'active' => request()->routeIs('cash-flows.*'),
                'icon' => 'fa-solid fa-wallet',
            ],
            [
                'label' => 'Category',
                'url' => route('categories.index'),
                'active' => request()->routeIs('categories.*'),
                'icon' => 'fa-solid fa-tags',
            ],
            [
                'label' => 'Reminder',
                'url' => route('reminder.index'),
                'active' => request()->routeIs('reminder.*'),
                'icon' => 'fa-solid fa-bell',
            ],
        ];
@endphp

<aside class="brutal-panel noise-overlay flex h-full flex-col justify-between overflow-y-auto p-5">
    <div class="relative z-10 space-y-6">
        <div class="space-y-3">
            <a href="{{ $homeRoute }}"
                class="inline-flex items-center gap-3 border-[4px] border-current bg-white px-3 py-2 text-black">
                <x-application-logo />
                <span class="text-sm font-black uppercase tracking-[0.24em]">Money Tracker</span>
            </a>
        </div>

        <nav class="space-y-3">
            @foreach ($navItems as $item)
                <a href="{{ $item['url'] }}"
                    class="flex items-center gap-3 border-[4px] border-current px-4 py-3 text-sm font-black uppercase tracking-[0.18em] {{ $item['active'] ? 'bg-[#FF6B6B]' : 'bg-white' }}">
                    <i class="{{ $item['icon'] }} w-5 text-center" aria-hidden="true"></i>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="brutal-panel-violet p-4" data-gold-price-widget data-url="{{ route('gold-price.show') }}">
            <p class="text-xs font-black uppercase tracking-[0.2em]">Current Gold Price</p>
            <p class="mt-2 text-sm font-black uppercase" data-gold-price-value>Loading...</p>
            <p class="mt-1 text-[11px] font-bold uppercase tracking-[0.12em] text-black/70" data-gold-price-source>
                Sumber : Pegadaian</p>
        </div>

        {{-- @if ($readonlyMember)
            <div class="brutal-panel-accent p-4">
                <p class="text-xs font-black uppercase tracking-[0.2em]">Readonly Member</p>
                <p class="mt-2 text-xl font-black uppercase">{{ $readonlyMember->name }}</p>
                <p class="mt-1 break-all text-sm font-bold">{{ $readonlyMember->email }}</p>
            </div>
        @endif --}}
    </div>

    <div class="relative z-10">
        @if ($readonlyMember)
            <form method="POST" action="{{ route('admin.impersonation.destroy') }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="brutal-btn brutal-btn-ghost w-full justify-center">
                    Stop Impersonate
                </button>
            </form>
        @else
            <div class="flex items-stretch gap-3">
                <a href="{{ route('profile.edit') }}"
                    class="flex flex-1 items-center justify-center border-[4px] border-current bg-white px-4 py-3 text-black"
                    aria-label="Profile">
                    <i class="fa-solid fa-user-gear text-base" aria-hidden="true"></i>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit" class="brutal-btn w-full justify-center px-4 py-3" aria-label="Log Out">
                        <i class="fa-solid fa-right-from-bracket text-base" aria-hidden="true"></i>
                    </button>
                </form>
            </div>
        @endif
    </div>
</aside>
