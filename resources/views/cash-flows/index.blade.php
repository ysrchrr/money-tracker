<x-app-layout>
    @php($readonly = $isReadonlyImpersonation ?? false)

    <x-slot name="header">
        <div class="space-y-2">
            <p class="text-xs font-black uppercase tracking-[0.22em]">Transaction Ledger</p>
            <h1 class="text-3xl font-black uppercase">Cash Flows</h1>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if ($period)
            <section class="brutal-panel-accent p-5">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="brutal-badge -rotate-2 bg-[#FF6B6B]">{{ $period['label'] }}</span>
                    <span class="brutal-badge rotate-2 bg-white">
                        {{ $period['start']->format('d M') }} - {{ $period['end']->format('d M Y') }}
                    </span>
                    <span class="brutal-badge bg-[#C4B5FD]">
                        {{ $period['is_cutoff_enabled'] ? 'Mode Cutoff 26-25' : 'Mode Kalender' }}
                    </span>
                </div>
            </section>
        @endif

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

        <section class="{{ $readonly ? 'grid gap-6' : 'grid gap-6 xl:grid-cols-[minmax(0,1fr)_430px]' }}">
            @if (!$readonly)
                <div class="brutal-panel p-6">
                    <p class="text-xs font-black uppercase tracking-[0.22em]">
                        {{ $editingCashFlow ? 'Edit Transaction' : 'Add Transaction' }}</p>
                    <h2 class="mt-2 text-3xl font-black uppercase">
                        {{ $editingCashFlow ? $editingCashFlow->description : 'Quick Input' }}</h2>

                    <form method="POST"
                        action="{{ $editingCashFlow ? route('cash-flows.update', $editingCashFlow) : route('cash-flows.store') }}"
                        class="mt-6 space-y-5">
                        @csrf
                        @if ($editingCashFlow)
                            @method('PATCH')
                        @endif

                        @php($currentType = old('type', $editingCashFlow?->type ?? 'expense'))
                        <div>
                            <span class="brutal-label">Type</span>
                            <div class="transaction-type-switch mt-2">
                                <label class="transaction-type-choice transaction-type-expense">
                                    <input type="radio" name="type" value="expense" @checked($currentType === 'expense')>
                                    <span>Expenses</span>
                                </label>
                                <label class="transaction-type-choice transaction-type-income">
                                    <input type="radio" name="type" value="income" @checked($currentType === 'income')>
                                    <span>Income</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_220px]">
                            <div>
                                <label class="brutal-label" for="description">Description</label>
                                <input id="description" name="description" class="brutal-input" type="text"
                                    value="{{ old('description', $editingCashFlow?->description) }}"
                                    placeholder="Beli kopi, freelance, transport">
                            </div>

                            <div>
                                <label class="brutal-label" for="amount">Amount</label>
                                <input id="amount" name="amount" class="brutal-input text-2xl font-black uppercase"
                                    type="text"
                                    value="{{ old('amount', $editingCashFlow ? number_format($editingCashFlow->amount, 0, ',', '.') : '') }}"
                                    placeholder="25.000" data-money-format>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="brutal-label" for="category_id">Category</label>
                                <select id="category_id" name="category_id" class="brutal-select">
                                    <option value="">-</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected((string) old('category_id', $editingCashFlow?->category_id) === (string) $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="brutal-label" for="transaction_date">Date</label>
                                <input id="transaction_date" name="transaction_date" class="brutal-input" type="date"
                                    value="{{ old('transaction_date', optional($editingCashFlow?->transaction_date)->toDateString() ?? now()->toDateString()) }}">
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <button type="submit"
                                class="brutal-btn">{{ $editingCashFlow ? 'Update Transaction' : 'Save Transaction' }}</button>
                            @if ($editingCashFlow)
                                <a href="{{ route('cash-flows.index') }}"
                                    class="brutal-btn brutal-btn-ghost">Cancel</a>
                            @endif
                        </div>
                    </form>
                </div>
            @endif

            <div class="brutal-panel-violet p-6">
                <p class="text-xs font-black uppercase tracking-[0.22em]">Filters</p>
                <form method="GET" class="mt-5 grid gap-4">
                    <input type="month" name="month" value="{{ $filters['month'] ?? '' }}" class="brutal-input">
                    @if ($activeMember->is_cutoff_enabled)
                        <p class="text-xs font-black uppercase tracking-[0.18em]">
                            Bulan filter mengikuti periode cutoff 26-25.
                        </p>
                    @endif
                    <select name="type" class="brutal-select">
                        <option value="">All Types</option>
                        <option value="income" @selected(($filters['type'] ?? '') === 'income')>Income</option>
                        <option value="expense" @selected(($filters['type'] ?? '') === 'expense')>Expense</option>
                    </select>
                    <select name="category_id" class="brutal-select">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) ($filters['category_id'] ?? '') === (string) $category->id)>{{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="brutal-input"
                        placeholder="Cari keterangan">

                    <div class="flex flex-wrap gap-3">
                        <button type="submit" class="brutal-btn brutal-btn-secondary">Apply Filter</button>
                        <a href="{{ route('cash-flows.index') }}" class="brutal-btn brutal-btn-ghost">Reset</a>
                    </div>
                </form>
            </div>
        </section>

        <section class="brutal-panel p-6">
            <div class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em]">Ledger</p>
                    <h2 class="mt-2 text-3xl font-black uppercase">Transactions</h2>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="brutal-ledger min-w-full" data-datatable="transactions" data-disable-sorting="true"
                    data-mobile-stack="true"
                    data-full-border="true">
                    <thead>
                        <tr>
                            <th class="text-center">Date</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Amount</th>
                            @if (!$readonly)
                                <th class="no-sort text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cashFlows as $cashFlow)
                            <tr>
                                <td class="text-center text-sm font-bold uppercase" data-label="Date"
                                    data-order="{{ $cashFlow->transaction_date->format('Y-m-d') }}">
                                    {{ $cashFlow->transaction_date->format('d M Y') }}</td>
                                <td class="text-base font-black uppercase" data-label="Description">{{ $cashFlow->description }}</td>
                                <td class="text-center text-sm font-bold uppercase" data-label="Category">{{ $cashFlow->category?->name ?? '-' }}</td>
                                <td
                                    data-label="Type"
                                    class="text-center text-sm font-black uppercase {{ $cashFlow->type === 'expense' ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $cashFlow->type }}
                                </td>
                                <td class="text-right text-base font-black uppercase" data-label="Amount"
                                    data-order="{{ $cashFlow->type === 'income' ? $cashFlow->amount : -$cashFlow->amount }}">
                                    Rp{{ number_format($cashFlow->amount, 0, ',', '.') }}
                                </td>
                                @if (!$readonly)
                                    <td class="text-center" data-label="Action">
                                        <div class="flex flex-wrap justify-center gap-2">
                                            <a href="{{ route('cash-flows.index', array_merge(request()->query(), ['edit' => $cashFlow->id])) }}"
                                                class="brutal-btn brutal-btn-secondary px-2 py-1.5 text-xs">Edit</a>
                                            <form method="POST"
                                                action="{{ route('cash-flows.destroy', $cashFlow) }}"
                                                data-confirm-delete data-confirm-title="Hapus transaksi?"
                                                data-confirm-text="Data cash flow yang dihapus tidak bisa dikembalikan.">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="brutal-btn px-2 py-1.5 text-xs">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
