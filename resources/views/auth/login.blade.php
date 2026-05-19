<x-guest-layout>
    <div class="space-y-6">
        <div class="space-y-3">
            <p class="text-xs font-black uppercase tracking-[0.22em]">Member Login</p>
            <h2 class="text-4xl font-black uppercase leading-none">Masuk dan lanjut catat</h2>
            <p class="text-base font-bold">
                Gunakan akunmu untuk masuk
            </p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" type="text" name="email" :value="old('email')" required autofocus
                    autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <label for="remember_me" class="flex items-center gap-3 border-[4px] border-current bg-white px-4 py-3">
                <input id="remember_me" type="checkbox" class="h-5 w-5 border-[3px] border-current accent-black"
                    name="remember">
                <span class="text-sm font-black uppercase tracking-[0.18em]">{{ __('Remember me') }}</span>
            </label>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                @if (Route::has('password.request'))
                    <a class="brutal-link" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-primary-button class="w-full justify-center sm:w-auto">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>

        <div class="border-[4px] border-current bg-[#C4B5FD] px-4 py-3 text-sm font-black uppercase tracking-[0.18em]">
            Belum punya akun?
            <a href="{{ route('register') }}" class="underline">Register</a>
        </div>
    </div>
</x-guest-layout>
