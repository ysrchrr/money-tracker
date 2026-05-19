<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <p class="text-xs font-black uppercase tracking-[0.22em]">Period Overview</p>
            <h1 class="text-3xl font-black uppercase sm:text-4xl">Dashboard</h1>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="noise-overlay brutal-panel relative p-6 sm:p-8">
            <div class="relative z-10 grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px] xl:items-end">
                <div class="space-y-5">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="brutal-badge bg-[#FF6B6B]">{{ $period['label'] }}</span>
                        <span class="brutal-badge bg-[#C4B5FD]">
                            {{ $period['start']->format('d M') }} - {{ $period['end']->format('d M Y') }}
                        </span>
                        {{-- <span class="brutal-badge bg-white">
                            {{ $period['is_cutoff_enabled'] ? 'Cutoff 26-25' : 'Kalender 1-Akhir Bulan' }}
                        </span> --}}
                    </div>

                    <div>
                        <p class="headline-stroke text-3xl font-black uppercase leading-none sm:text-6xl">Net cash</p>
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
            <p class="text-xs font-black uppercase tracking-[0.22em]">Spending by Category</p>
            <div class="mt-5 grid gap-4 xl:grid-cols-2">
                @forelse ($categorySpend as $category)
                    <div class="space-y-2 border-[4px] border-current bg-white p-4">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-lg font-black uppercase">{{ $category['name'] }}</p>
                                <p class="text-sm font-bold">
                                    Rp{{ number_format($category['amount'], 0, ',', '.') }} / saving
                                    {{ rtrim(rtrim(number_format($category['percentage'], 2, '.', ''), '0'), '.') }}%
                                </p>
                            </div>
                        </div>
                        <div class="border-[4px] border-current bg-white p-1">
                            <div class="h-6 bg-[#FF6B6B]"
                                style="width: {{ max(12, min(100, $expense > 0 ? round(($category['amount'] / $expense) * 100) : 12)) }}%">
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="border-[4px] border-current bg-white px-4 py-4 text-sm font-black uppercase">
                        Oops, belum ada pengeluaran nih
                    </div>
                @endforelse
            </div>
        </section>

        <section class="brutal-panel p-6">
            <div class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em]">Recent Ledger</p>
                    <h2 class="mt-2 text-3xl font-black uppercase">Last Transactions</h2>
                </div>
                <a href="{{ route('cash-flows.index') }}" class="brutal-badge bg-[#C4B5FD] rotate-2">Open Cash Flow</a>
            </div>

            <div class="overflow-x-auto">
                <table class="brutal-ledger min-w-full" data-mobile-stack="true">
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
                        @forelse ($recentTransactions as $transaction)
                            <tr>
                                <td class="text-sm font-bold uppercase" data-label="Date">
                                    {{ $transaction->transaction_date->format('d M Y') }}</td>
                                <td class="text-base font-black uppercase" data-label="Description">{{ $transaction->description }}</td>
                                <td class="text-sm font-bold uppercase" data-label="Category">{{ $transaction->category?->name ?? '-' }}</td>
                                <td class="text-sm font-black uppercase" data-label="Type">{{ $transaction->type }}</td>
                                <td
                                    data-label="Amount"
                                    class="text-right text-base font-black uppercase {{ $transaction->type === 'income' ? 'text-[#1F8A3B]' : 'text-[#C1121F]' }}">
                                    {{ $transaction->type === 'income' ? '+' : '-' }}
                                    Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-sm font-black uppercase">Belum ada transaksi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
