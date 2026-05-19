<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <p class="text-xs font-black uppercase tracking-[0.22em]">Member Detail</p>
            <h1 class="text-3xl font-black uppercase">{{ $member->name }}</h1>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="brutal-panel p-6">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em]">{{ $member->member_type }} member</p>
                    <h2 class="mt-2 break-all text-4xl font-black uppercase">{{ $member->email }}</h2>
                    <p class="mt-3 text-sm font-bold uppercase">Joined {{ $member->created_at->format('d M Y') }}</p>
                </div>

                <div class="flex flex-col gap-3 lg:items-end">
                    <button
                        type="button"
                        class="brutal-btn brutal-btn-ghost"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'update-member-type')"
                    >
                        Update Status
                    </button>

                    <form method="POST" action="{{ route('admin.members.impersonate', $member) }}">
                        @csrf
                        <button type="submit" class="brutal-btn">Impersonate Readonly</button>
                    </form>
                </div>
            </div>
        </section>

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="brutal-stat bg-[#FFD93D]">
                <p class="brutal-stat-title">Transactions</p>
                <p class="brutal-stat-value">{{ number_format($transactionsCount, 0, ',', '.') }}</p>
            </div>
            <div class="brutal-stat bg-[#FF6B6B]">
                <p class="brutal-stat-title">Expense</p>
                <p class="brutal-stat-value">Rp{{ number_format($expense, 0, ',', '.') }}</p>
            </div>
            <div class="brutal-stat bg-white">
                <p class="brutal-stat-title">Net</p>
                <p class="brutal-stat-value">Rp{{ number_format($net, 0, ',', '.') }}</p>
            </div>
            <div class="brutal-stat bg-[#C4B5FD]">
                <p class="brutal-stat-title">Must Saving</p>
                <p class="brutal-stat-value">Rp{{ number_format($mustSaving, 0, ',', '.') }}</p>
            </div>
        </section>

        <section class="brutal-panel p-6">
            <div class="mb-5">
                <p class="text-xs font-black uppercase tracking-[0.22em]">Recent Ledger</p>
                <h2 class="mt-2 text-3xl font-black uppercase">Last 20 Transactions</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="brutal-ledger min-w-full">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cashFlows as $cashFlow)
                            <tr>
                                <td class="text-sm font-bold uppercase">{{ $cashFlow->transaction_date->format('d M Y') }}</td>
                                <td class="text-base font-black uppercase">{{ $cashFlow->description }}</td>
                                <td class="text-sm font-bold uppercase">{{ $cashFlow->category?->name ?? '-' }}</td>
                                <td class="text-sm font-black uppercase">{{ $cashFlow->type }}</td>
                                <td class="text-right text-base font-black uppercase">
                                    {{ $cashFlow->type === 'income' ? '+' : '-' }} Rp{{ number_format($cashFlow->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-sm font-black uppercase">Belum ada transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <x-modal name="update-member-type" :show="$errors->has('member_type')" maxWidth="lg" focusable>
        <form method="POST" action="{{ route('admin.members.update', $member) }}" class="bg-white">
            @csrf
            @method('PATCH')

            <div class="border-b-4 border-black bg-[#F3EFE6] px-6 py-4">
                <p class="text-[11px] font-black uppercase tracking-[0.22em]">Member Status</p>
                <h2 class="mt-2 text-2xl font-black uppercase">Update {{ $member->name }}</h2>
            </div>

            <div class="space-y-4 px-6 py-5">
                <p class="text-sm font-bold uppercase text-neutral-700">Pilih tipe member lalu simpan.</p>

                <div class="border-4 border-black">
                    <label for="member-type-silver"
                        class="grid cursor-pointer grid-cols-[1fr_auto] items-center gap-4 border-b-4 border-black bg-white px-4 py-4">
                        <div>
                            <p class="text-xl font-black uppercase leading-none">Silver</p>
                            <p class="mt-2 text-xs font-bold uppercase text-neutral-600">Default member tier</p>
                        </div>
                        <input
                            id="member-type-silver"
                            type="radio"
                            name="member_type"
                            value="silver"
                            class="h-5 w-5 border-2 border-black text-black focus:ring-0"
                            @checked(old('member_type', $member->member_type) === 'silver')
                        >
                    </label>

                    <label for="member-type-gold"
                        class="grid cursor-pointer grid-cols-[1fr_auto] items-center gap-4 bg-[#FFD93D] px-4 py-4">
                        <div>
                            <p class="text-xl font-black uppercase leading-none">Gold</p>
                            <p class="mt-2 text-xs font-bold uppercase text-neutral-700">Prioritas member tier</p>
                        </div>
                        <input
                            id="member-type-gold"
                            type="radio"
                            name="member_type"
                            value="gold"
                            class="h-5 w-5 border-2 border-black text-black focus:ring-0"
                            @checked(old('member_type', $member->member_type) === 'gold')
                        >
                    </label>
                </div>

                @error('member_type')
                    <p class="text-sm font-black uppercase text-[#C1121F]">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-3 border-t-4 border-black bg-[#F3EFE6] px-6 py-4 sm:flex-row sm:justify-end">
                <button type="button" class="brutal-btn brutal-btn-ghost" x-on:click="$dispatch('close')">Batal</button>
                <button type="submit" class="brutal-btn">Simpan</button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
