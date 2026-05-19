<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <p class="text-xs font-black uppercase tracking-[0.22em]">Admin Console</p>
            <h1 class="text-3xl font-black uppercase">All Member Dashboard</h1>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="noise-overlay brutal-panel relative p-6 sm:p-8">
            <div class="relative z-10 grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px] xl:items-end">
                <div class="space-y-5">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="brutal-badge -rotate-2 bg-[#FF6B6B]">{{ $period['label'] }}</span>
                        <span class="brutal-badge rotate-2 bg-[#C4B5FD]">
                            {{ $period['start']->format('d M') }} - {{ $period['end']->format('d M Y') }}
                        </span>
                    </div>

                    <div>
                        <p class="headline-stroke text-3xl font-black uppercase leading-none sm:text-6xl">Global net</p>
                        <p class="mt-2 break-words text-4xl font-black uppercase leading-none sm:text-7xl xl:text-8xl">
                            Rp{{ number_format($net, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <form method="GET" class="flex flex-col gap-3">
                    <input type="month" name="month" value="{{ $period['month'] }}" class="brutal-input">
                    <button type="submit" class="brutal-btn justify-center">Filter Period</button>
                </form>
            </div>
        </section>

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="brutal-stat bg-[#FFD93D]">
                <p class="brutal-stat-title">Members</p>
                <p class="brutal-stat-value">{{ number_format($totalMembers, 0, ',', '.') }}</p>
            </div>
            <div class="brutal-stat bg-[#7BD389]">
                <p class="brutal-stat-title">Active This Period</p>
                <p class="brutal-stat-value">{{ number_format($activeMembers, 0, ',', '.') }}</p>
            </div>
            <div class="brutal-stat bg-[#FF6B6B]">
                <p class="brutal-stat-title">Expense</p>
                <p class="brutal-stat-value">Rp{{ number_format($expense, 0, ',', '.') }}</p>
            </div>
            <div class="brutal-stat bg-[#C4B5FD]">
                <p class="brutal-stat-title">Must Saving</p>
                <p class="brutal-stat-value">Rp{{ number_format($mustSaving, 0, ',', '.') }}</p>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_420px]">
            <div class="brutal-panel p-6">
                <div class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.22em]">Member Ranking</p>
                        <h2 class="mt-2 text-3xl font-black uppercase">Top Expense</h2>
                    </div>
                    <a href="{{ route('admin.members.index') }}" class="brutal-badge bg-[#FFD93D] rotate-2">Open Members</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="brutal-ledger min-w-full" data-mobile-stack="true">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th class="text-right">Income</th>
                                <th class="text-right">Expense</th>
                                <th class="text-right">Net</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($memberSummary as $summary)
                                <tr>
                                    <td data-label="Member">
                                        <p class="text-base font-black uppercase">{{ $summary['user']?->name ?? '-' }}</p>
                                        <p class="text-xs font-bold">{{ $summary['user']?->email ?? '-' }}</p>
                                    </td>
                                    <td class="text-right text-sm font-black uppercase" data-label="Income">Rp{{ number_format($summary['income'], 0, ',', '.') }}</td>
                                    <td class="text-right text-sm font-black uppercase" data-label="Expense">Rp{{ number_format($summary['expense'], 0, ',', '.') }}</td>
                                    <td class="text-right text-sm font-black uppercase" data-label="Net">Rp{{ number_format($summary['net'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-sm font-black uppercase">Belum ada transaksi periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="brutal-panel-violet p-6">
                <p class="text-xs font-black uppercase tracking-[0.22em]">Category Pressure</p>
                <div class="mt-5 space-y-4">
                    @forelse ($categorySpend as $category)
                        <div class="border-[4px] border-current bg-white p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-lg font-black uppercase">{{ $category['name'] }}</p>
                                    <p class="text-sm font-bold">{{ $category['transactions'] }} transaksi</p>
                                </div>
                                <p class="text-right text-lg font-black uppercase">Rp{{ number_format($category['amount'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="border-[4px] border-current bg-white px-4 py-4 text-sm font-black uppercase">
                            Belum ada expense.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="brutal-panel p-6">
            <div class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em]">Latest Ledger</p>
                    <h2 class="mt-2 text-3xl font-black uppercase">All Transactions</h2>
                </div>
                <a href="{{ route('admin.transactions.index') }}" class="brutal-badge bg-[#C4B5FD] rotate-2">Open Transactions</a>
            </div>

            <div class="overflow-x-auto">
                <table class="brutal-ledger min-w-full" data-mobile-stack="true">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Member</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentTransactions as $transaction)
                            <tr>
                                <td class="text-sm font-bold uppercase" data-label="Date">{{ $transaction->transaction_date->format('d M Y') }}</td>
                                <td data-label="Member">
                                    <p class="text-sm font-black uppercase">{{ $transaction->user?->name ?? '-' }}</p>
                                    <p class="text-xs font-bold">{{ $transaction->user?->email ?? '-' }}</p>
                                </td>
                                <td class="text-base font-black uppercase" data-label="Description">{{ $transaction->description }}</td>
                                <td class="text-sm font-black uppercase" data-label="Type">{{ $transaction->type }}</td>
                                <td class="text-right text-base font-black uppercase" data-label="Amount">
                                    {{ $transaction->type === 'income' ? '+' : '-' }} Rp{{ number_format($transaction->amount, 0, ',', '.') }}
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
</x-app-layout>
