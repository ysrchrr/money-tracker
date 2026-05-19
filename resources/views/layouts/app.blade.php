<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('MT.png') }}">

    <title>{{ config('app.name', 'Money Tracker') }}</title>

    <script>
        (() => {
            const savedTheme = localStorage.getItem('money-tracker-theme');
            const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.setAttribute('data-theme', savedTheme || (systemDark ? 'dark' : 'light'));
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
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

    <div class="brutal-shell">
        <div class="brutal-container grid min-h-screen gap-6 py-6 lg:grid-cols-[280px_minmax(0,1fr)]">
            <div class="hidden lg:sticky lg:top-6 lg:block lg:h-[calc(100vh-3rem)] lg:self-start">
                @include('layouts.navigation')
            </div>

            <div class="space-y-6">
                <header class="brutal-panel sticky top-3 z-30 p-4 sm:top-5 sm:p-5 lg:top-6">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex items-center justify-between gap-3 lg:hidden">
                            <a href="{{ $homeRoute }}"
                                class="inline-flex items-center gap-3 border-[4px] border-current bg-white px-3 py-2 text-black">
                                <x-application-logo />
                                <span class="text-sm font-black uppercase tracking-[0.24em]">Money Tracker</span>
                            </a>
                            <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle theme">
                                <i class="fa-solid fa-sun" data-theme-icon aria-hidden="true"></i>
                            </button>
                        </div>

                        <div class="hidden lg:flex lg:items-center lg:gap-3">
                            @if ($isAdminNavigation)
                                <span class="brutal-badge -rotate-2">Admin Console</span>
                                <span class="brutal-badge rotate-2 bg-[#C4B5FD]">All Member View</span>
                            @elseif ($readonlyMember)
                                <span class="brutal-badge -rotate-2 bg-[#FF6B6B]">Readonly Impersonation</span>
                                <span class="brutal-badge rotate-2 bg-[#C4B5FD]">{{ $readonlyMember->email }}</span>
                            @endif
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between lg:flex-1">
                            <div>
                                @isset($header)
                                    {{ $header }}
                                @else
                                    <h1 class="text-3xl font-black uppercase">Dashboard</h1>
                                @endisset
                            </div>

                            <div class="flex items-center gap-3 self-start sm:self-auto">
                                <div
                                    class="border-[4px] border-current bg-[#FFD93D] px-4 py-2 text-xs font-black uppercase tracking-[0.18em]">
                                    {{ now()->format('d M Y') }}
                                </div>
                                <button type="button" class="theme-toggle hidden lg:inline-flex" data-theme-toggle
                                    aria-label="Toggle theme">
                                    <i class="fa-solid fa-sun" data-theme-icon aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </header>

                <nav class="flex gap-3 overflow-x-auto lg:hidden">
                    @foreach ($navItems as $item)
                        <a href="{{ $item['url'] }}"
                            class="inline-flex items-center gap-3 whitespace-nowrap border-[4px] border-current px-4 py-3 text-sm font-black uppercase tracking-[0.18em] {{ $item['active'] ? 'bg-[#FF6B6B]' : 'bg-white' }}">
                            <i class="{{ $item['icon'] }} w-5 text-center" aria-hidden="true"></i>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>

                <main>
                    {{-- @if (($isReadonlyImpersonation ?? false) && $readonlyMember)
                        <div class="mb-6 border-[4px] border-current bg-[#FF6B6B] px-4 py-4 text-black">
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-[0.22em]">Readonly as Admin</p>
                                    <p class="mt-1 text-lg font-black uppercase">{{ $readonlyMember->name }} /
                                        {{ $readonlyMember->email }}</p>
                                </div>
                                <form method="POST" action="{{ route('admin.impersonation.destroy') }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="brutal-btn brutal-btn-ghost">Stop Impersonate</button>
                                </form>
                            </div>
                        </div>
                    @endif --}}

                    @include('layouts.toasts')

                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>
</body>

</html>
