<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <div class="brutal-shell">
        <header class="brutal-container py-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-3 border-[4px] border-current bg-white px-3 py-2 text-black">
                    <x-application-logo />
                    <span class="text-sm font-black uppercase tracking-[0.24em]">Money Tracker</span>
                </a>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle theme">
                        <i class="fa-solid fa-sun" data-theme-icon aria-hidden="true"></i>
                    </button>
                    <a href="{{ route('login') }}" class="brutal-btn brutal-btn-ghost">Login</a>
                    <a href="{{ route('register') }}" class="brutal-btn">Register</a>
                </div>
            </div>
        </header>

        <main class="brutal-container space-y-8 pb-10">
            <section class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="noise-overlay brutal-panel-yellow relative p-6 sm:p-8 lg:p-10">
                    <div class="relative z-10 space-y-6">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="brutal-badge -rotate-2 bg-[#C4B5FD]">Buat Masa Depanmu Lebih Baik</span>
                        </div>

                        <div class="space-y-4">
                            <p class="text-sm font-black uppercase tracking-[0.24em]">Personal Finance Control Board</p>
                            <h1 class="max-w-4xl text-5xl font-black uppercase leading-[0.88] sm:text-7xl">
                                Manage your money, build your future
                            </h1>
                            <p class="max-w-2xl text-lg font-bold sm:text-xl">
                                Money Tracker helps you record income, expenses, savings targets, and daily financial
                                habits in one place.
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('register') }}" class="brutal-btn">Start Tracking</a>
                            <a href="{{ route('login') }}" class="brutal-btn brutal-btn-secondary">Masuk Sekarang</a>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="brutal-panel h-full p-5 sm:p-6">
                        <div class="mb-4 flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.22em]">Live Preview</p>
                                <h2 class="mt-2 text-3xl font-black uppercase">This Month</h2>
                            </div>
                            <span class="brutal-badge bg-[#FF6B6B] rotate-2">Monthly Overview</span>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="brutal-stat bg-[#FFD93D]">
                                <p class="brutal-stat-title">Income</p>
                                <p class="brutal-stat-value">Rp12.4 Jt</p>
                            </div>
                            <div class="brutal-stat bg-[#FF6B6B]">
                                <p class="brutal-stat-title">Expense</p>
                                <p class="brutal-stat-value">Rp7.3 Jt</p>
                            </div>
                            <div class="brutal-stat bg-white">
                                <p class="brutal-stat-title">Net Cash</p>
                                <p class="brutal-stat-value">Rp5.0 Jt</p>
                            </div>
                            <div class="brutal-stat bg-[#C4B5FD]">
                                <p class="brutal-stat-title">Must Saving</p>
                                <p class="brutal-stat-value">Rp1.4 Jt</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        @include('layouts.toasts')
    </div>
</body>

</html>
