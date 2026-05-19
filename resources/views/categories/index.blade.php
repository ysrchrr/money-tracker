<x-app-layout>
    @php($readonly = $isReadonlyImpersonation ?? false)

    <x-slot name="header">
        <div class="space-y-2">
            <p class="text-xs font-black uppercase tracking-[0.22em]">Reference Data</p>
            <h1 class="text-3xl font-black uppercase">Categories</h1>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if (! $readonly)
            <section class="brutal-panel p-6">
                <p class="text-xs font-black uppercase tracking-[0.22em]">{{ $editingCategory ? 'Edit Category' : 'Create Category' }}</p>
                <h2 class="mt-2 text-3xl font-black uppercase">{{ $editingCategory ? $editingCategory->name : 'New Category' }}</h2>

                <form method="POST" action="{{ $editingCategory ? route('categories.update', $editingCategory) : route('categories.store') }}" class="mt-6 space-y-5">
                    @csrf
                    @if ($editingCategory)
                        @method('PATCH')
                    @endif

                    <div>
                        <label class="brutal-label" for="name">Category Name</label>
                        <input id="name" name="name" class="brutal-input" type="text" value="{{ old('name', $editingCategory?->name) }}" placeholder="FNB, Transport, Health">
                    </div>

                    <div>
                        <label class="brutal-label" for="percentage">Percentage</label>
                        <input id="percentage" name="percentage" class="brutal-input" type="number" step="0.01" min="0" max="100" value="{{ old('percentage', $editingCategory?->percentage) }}" placeholder="20">
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button type="submit" class="brutal-btn">{{ $editingCategory ? 'Update Category' : 'Save Category' }}</button>
                        @if ($editingCategory)
                            <a href="{{ route('categories.index') }}" class="brutal-btn brutal-btn-ghost">Cancel</a>
                        @endif
                    </div>
                </form>
            </section>
        @endif

        <section class="brutal-panel-violet p-6">
            <p class="text-xs font-black uppercase tracking-[0.22em]">Category List</p>
            <div class="mt-5 overflow-x-auto">
                <table class="brutal-ledger min-w-full" data-datatable="categories" data-mobile-stack="true">
                    <thead>
                        <tr>
                            <th class="text-center">Category</th>
                            <th class="text-center">Must Saving</th>
                            <th class="text-center">Transactions</th>
                            @if (! $readonly)
                                <th class="no-sort text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td class="text-2xl font-black uppercase" data-label="Category">{{ $category->name }}</td>
                                <td class="text-right text-base font-black uppercase" data-label="Must Saving" data-order="{{ $category->percentage }}">
                                    {{ rtrim(rtrim(number_format($category->percentage, 2, '.', ''), '0'), '.') }}%
                                </td>
                                <td class="text-right text-base font-black uppercase" data-label="Transactions" data-order="{{ $category->cash_flows_count }}">
                                    {{ $category->cash_flows_count }} transaksi
                                </td>
                                @if (! $readonly)
                                    <td data-label="Action">
                                        <div class="flex flex-wrap justify-end gap-2">
                                            <a href="{{ route('categories.index', ['edit' => $category->id]) }}" class="brutal-btn brutal-btn-secondary px-3 py-2">Edit</a>
                                            <form method="POST" action="{{ route('categories.destroy', $category) }}" data-confirm-delete data-confirm-title="Hapus category?" data-confirm-text="Category yang dihapus tidak bisa dikembalikan.">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="brutal-btn px-3 py-2">Delete</button>
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
