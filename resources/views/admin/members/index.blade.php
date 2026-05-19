<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <p class="text-xs font-black uppercase tracking-[0.22em]">Admin Directory</p>
            <h1 class="text-3xl font-black uppercase">Members</h1>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="brutal-panel-violet p-4">
            <p class="text-xs font-black uppercase tracking-[0.22em]">Filters</p>
            <form method="GET" class="mt-3 flex flex-col gap-3 lg:flex-row">
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="brutal-input py-2 text-sm lg:mt-0"
                    placeholder="Cari nama/email member">
                <div class="flex flex-wrap gap-2 lg:ml-auto">
                    <button type="submit" class="brutal-btn brutal-btn-secondary px-3 py-2 text-sm">Apply</button>
                    <a href="{{ route('admin.members.index') }}" class="brutal-btn brutal-btn-ghost px-3 py-2 text-sm">Reset</a>
                </div>
            </form>
        </section>

        <section class="brutal-panel p-6">
            <div class="mb-5">
                <p class="text-xs font-black uppercase tracking-[0.22em]">Member List</p>
                <h2 class="mt-2 text-3xl font-black uppercase">{{ number_format($members->count(), 0, ',', '.') }}
                    Members</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="brutal-ledger min-w-full" data-datatable="admin-members">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Type</th>
                            <th class="text-right">Transactions</th>
                            <th class="text-right">Income</th>
                            <th class="text-right">Expense</th>
                            <th>Last Transaction</th>
                            <th class="no-sort text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($members as $member)
                            <tr>
                                <td>
                                    <p class="text-base font-black uppercase">{{ $member->name }}</p>
                                    <p class="text-xs font-bold">{{ $member->email }}</p>
                                </td>
                                <td class="text-sm font-black uppercase">{{ $member->member_type }}</td>
                                <td class="text-right text-sm font-black uppercase"
                                    data-order="{{ $member->cash_flows_count }}">{{ $member->cash_flows_count }}</td>
                                <td class="text-right text-sm font-black uppercase"
                                    data-order="{{ $member->total_income ?? 0 }}">
                                    Rp{{ number_format($member->total_income ?? 0, 0, ',', '.') }}</td>
                                <td class="text-right text-sm font-black uppercase"
                                    data-order="{{ $member->total_expense ?? 0 }}">
                                    Rp{{ number_format($member->total_expense ?? 0, 0, ',', '.') }}</td>
                                <td class="text-sm font-bold uppercase"
                                    data-order="{{ $member->last_transaction_date ?? '' }}">
                                    {{ $member->last_transaction_date ? \Carbon\Carbon::parse($member->last_transaction_date)->format('d M Y') : '-' }}
                                </td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.members.show', $member) }}"
                                            class="brutal-btn brutal-btn-secondary px-3 py-2">Detail</a>
                                        <form method="POST"
                                            action="{{ route('admin.members.impersonate', $member) }}">
                                            @csrf
                                            <button type="submit" class="brutal-btn px-3 py-2">Impersonate</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
