<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <p class="text-xs font-black uppercase tracking-[0.22em]">Account Control</p>
            <h1 class="text-3xl font-black uppercase">{{ __('Profile') }}</h1>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="grid gap-6 xl:grid-cols-3">
            <div class="brutal-panel p-5 sm:p-6 xl:col-span-2">
                <div class="max-w-2xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="brutal-panel-violet p-5 sm:p-6">
                <div class="max-w-2xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="brutal-panel-accent p-5 sm:p-6 xl:col-span-3">
                <div class="max-w-2xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
