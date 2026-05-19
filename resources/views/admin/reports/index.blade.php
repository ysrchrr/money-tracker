<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <p class="text-xs font-black uppercase tracking-[0.22em]">Admin Summary</p>
            <h1 class="text-3xl font-black uppercase">Reports</h1>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="brutal-panel p-6">
            <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px] xl:items-end">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="brutal-badge -rotate-2 bg-[#FF6B6B]">{{ $period['label'] }}</span>
                        <span class="brutal-badge rotate-2 bg-[#C4B5FD]">
                            {{ $period['start']->format('d M') }} - {{ $period['end']->format('d M Y') }}
                        </span>
                    </div>
                    <h2 class="mt-5 text-4xl font-black uppercase">Global Report</h2>
                </div>

                <form method="GET" class="flex flex-col gap-3">
                    <input type="month" name="month" value="{{ $period['month'] }}" class="brutal-input">
                    <button type="submit" class="brutal-btn justify-center">Filter Period</button>
                </form>
            </div>
        </section>

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

        <section class="grid gap-6 xl:grid-cols-2">
            <div class="brutal-panel p-6">
                <p class="text-xs font-black uppercase tracking-[0.22em]">Per Member</p>
                <div class="mt-5 overflow-x-auto">
                    <table class="brutal-ledger min-w-full" data-datatable="report-members">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th class="text-right">Income</th>
                                <th class="text-right">Expense</th>
                                <th class="text-right">Net</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($memberComparison as $summary)
                                <tr>
                                    <td>
                                        <p class="text-sm font-black uppercase">{{ $summary['user']?->name ?? '-' }}</p>
                                        <p class="text-xs font-bold">{{ $summary['user']?->email ?? '-' }}</p>
                                    </td>
                                    <td class="text-right text-sm font-black uppercase">Rp{{ number_format($summary['income'], 0, ',', '.') }}</td>
                                    <td class="text-right text-sm font-black uppercase">Rp{{ number_format($summary['expense'], 0, ',', '.') }}</td>
                                    <td class="text-right text-sm font-black uppercase">Rp{{ number_format($summary['net'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="brutal-panel-violet p-6">
                <p class="text-xs font-black uppercase tracking-[0.22em]">Top Categories</p>
                <div class="mt-5 space-y-4">
                    @forelse ($categorySpend as $category)
                        <div class="space-y-3 border-[4px] border-current bg-white p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-lg font-black uppercase">{{ $category['name'] }}</p>
                                    <p class="text-sm font-bold">{{ $category['transactions'] }} transaksi / {{ $category['share'] }}%</p>
                                </div>
                                <p class="text-right text-lg font-black uppercase">Rp{{ number_format($category['amount'], 0, ',', '.') }}</p>
                            </div>
                            <div class="border-[4px] border-current bg-white p-1">
                                <div class="h-5 bg-[#FF6B6B]" style="width: {{ max(8, min(100, $category['share'])) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="border-[4px] border-current bg-white px-4 py-4 text-sm font-black uppercase">
                            Belum ada category spend.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
