<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $activeMember = $this->activeMember($request);

        $categories = $activeMember
            ->categories()
            ->withCount('cashFlows')
            ->orderBy('name')
            ->get();

        $editingCategory = null;

        if ($request->filled('edit')) {
            $editingCategory = $activeMember
                ->categories()
                ->whereKey($request->integer('edit'))
                ->first();
        }

        return view('categories.index', [
            'categories' => $categories,
            'editingCategory' => $editingCategory,
            'activeMember' => $activeMember,
        ]);
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $request->user()->categories()->create($request->validated());

        return back()->with('status', 'Category berhasil dibuat.');
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        abort_unless($category->user_id === $request->user()->id, 404);

        $category->update($request->validated());

        return to_route('categories.index')->with('status', 'Category berhasil diupdate.');
    }

    public function destroy(Request $request, Category $category): RedirectResponse
    {
        abort_unless($category->user_id === $request->user()->id, 404);

        if ($category->cashFlows()->exists()) {
            return back()->withErrors(['delete' => 'Category sudah dipakai transaksi dan tidak bisa dihapus.']);
        }

        $category->delete();

        return back()->with('status', 'Category berhasil dihapus.');
    }
}
