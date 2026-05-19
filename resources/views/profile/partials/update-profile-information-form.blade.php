<section>
    <header>
        <h2 class="text-3xl font-black uppercase">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-2 text-base font-bold">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="mt-2 text-sm font-bold">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="brutal-link">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 border-[4px] border-current bg-[#7BD389] px-4 py-3 text-sm font-black uppercase tracking-[0.18em] text-black">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="space-y-3 border-[4px] border-current bg-[#FFD93D] p-4 text-black">
            <div class="space-y-1">
                <p class="text-sm font-black uppercase tracking-[0.18em]">Metode Cutoff</p>
                <p class="text-sm font-bold">
                    Jika aktif, periode laporan dihitung dari tanggal 26 bulan sebelumnya sampai 25 bulan berjalan.
                    Cocok untuk pola gajian dan budgeting bulanan.
                </p>
            </div>

            @php($cutoffEnabled = old('is_cutoff_enabled', (int) $user->is_cutoff_enabled))

            <div class="grid gap-3 sm:grid-cols-2">
                <label class="border-[4px] border-current bg-white px-4 py-3">
                    <input type="radio" name="is_cutoff_enabled" value="1" class="mr-3" @checked((string) $cutoffEnabled === '1')>
                    <span class="text-sm font-black uppercase tracking-[0.18em]">Ya, aktifkan</span>
                </label>

                <label class="border-[4px] border-current bg-white px-4 py-3">
                    <input type="radio" name="is_cutoff_enabled" value="0" class="mr-3" @checked((string) $cutoffEnabled === '0')>
                    <span class="text-sm font-black uppercase tracking-[0.18em]">Tidak</span>
                </label>
            </div>

            <x-input-error class="mt-2" :messages="$errors->get('is_cutoff_enabled')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-black uppercase"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
