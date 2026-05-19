<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <p class="text-xs font-black uppercase tracking-[0.22em]">Admin Ledger</p>
            <h1 class="text-3xl font-black uppercase">Transactions</h1>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="brutal-stat bg-[#FFD93D]">
                <p class="brutal-stat-title">Income</p>
                <p class="brutal-stat-value">Rp{{ number_format($income, 0, ',', '.') }}</p>
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

        <section class="brutal-panel-violet p-6">
            <p class="text-xs font-black uppercase tracking-[0.22em]">Filters</p>
            <form method="GET" class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                <input type="month" name="month" value="{{ $filters['month'] ?? '' }}" class="brutal-input">
                <select name="member_id" class="brutal-select">
                    <option value="">All Members</option>
                    @foreach ($members as $member)
                        <option value="{{ $member->id }}" @selected((string) ($filters['member_id'] ?? '') === (string) $member->id)>{{ $member->name }}</option>
                    @endforeach
                </select>
                <select name="type" class="brutal-select">
                    <option value="">All Types</option>
                    <option value="income" @selected(($filters['type'] ?? '') === 'income')>Income</option>
                    <option value="expense" @selected(($filters['type'] ?? '') === 'expense')>Expense</option>
                </select>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="brutal-input" placeholder="Cari transaksi/member">
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="brutal-btn brutal-btn-secondary">Apply</button>
                    <a href="{{ route('admin.transactions.index') }}" class="brutal-btn brutal-btn-ghost">Reset</a>
                </div>
            </form>
        </section>

        <section class="brutal-panel p-6">
            <div class="mb-5">
                <p class="text-xs font-black uppercase tracking-[0.22em]">Readonly</p>
                <h2 class="mt-2 text-3xl font-black uppercase">{{ number_format($cashFlows->count(), 0, ',', '.') }} Transactions</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="brutal-ledger min-w-full" data-datatable="admin-transactions" data-mobile-stack="true">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Member</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cashFlows as $cashFlow)
                            <tr>
                                <td class="text-sm font-bold uppercase" data-label="Date" data-order="{{ $cashFlow->transaction_date->format('Y-m-d') }}">{{ $cashFlow->transaction_date->format('d M Y') }}</td>
                                <td data-label="Member">
                                    <p class="text-sm font-black uppercase">{{ $cashFlow->user?->name ?? '-' }}</p>
                                    <p class="text-xs font-bold">{{ $cashFlow->user?->email ?? '-' }}</p>
                                </td>
                                <td class="text-base font-black uppercase" data-label="Description">{{ $cashFlow->description }}</td>
                                <td class="text-sm font-bold uppercase" data-label="Category">{{ $cashFlow->category?->name ?? '-' }}</td>
                                <td class="text-sm font-black uppercase" data-label="Type">{{ $cashFlow->type }}</td>
                                <td class="text-right text-base font-black uppercase" data-label="Amount" data-order="{{ $cashFlow->type === 'income' ? $cashFlow->amount : -$cashFlow->amount }}">
                                    {{ $cashFlow->type === 'income' ? '+' : '-' }} Rp{{ number_format($cashFlow->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
