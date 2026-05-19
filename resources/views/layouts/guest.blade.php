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
    <div class="brutal-shell">
        <header class="brutal-container py-6">
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-3 border-[4px] border-current bg-white px-3 py-2 text-black">
                    <x-application-logo />
                    <span class="text-sm font-black uppercase tracking-[0.24em]">Money Tracker</span>
                </a>

                <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle theme">
                    <i class="fa-solid fa-sun" data-theme-icon aria-hidden="true"></i>
                </button>
            </div>
        </header>

        <main class="brutal-container pb-12 pt-4">
            <section class="mx-auto w-full max-w-2xl brutal-panel p-6 sm:p-8">
                {{ $slot }}
            </section>
        </main>

        @include('layouts.toasts')
    </div>
</body>

</html>
