<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCashFlowRequest;
use App\Http\Requests\UpdateCashFlowRequest;
use App\Models\CashFlow;
use App\Support\MoneyTrackerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CashFlowController extends Controller
{
    public function __construct(
        protected MoneyTrackerService $moneyTracker,
    ) {}

    public function index(Request $request): View
    {
        $activeMember = $this->activeMember($request);
        $query = $activeMember->cashFlows()->with('category');
        $period = null;
        $month = $request->string('month')->toString();

        if ($month !== '' || $this->moneyTracker->usesCutoff($activeMember)) {
            $period = $this->moneyTracker->periodForMonth($month !== '' ? $month : null, $activeMember);
            $query->whereBetween('transaction_date', [$period['start']->toDateString(), $period['end']->toDateString()]);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->string('type')->toString());
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('search')) {
            $query->where('description', 'like', '%'.$request->string('search').'%');
        }

        $summaryItems = (clone $query)->get();

        $cashFlows = $query
            ->latest('transaction_date')
            ->latest('id')
            ->get();

        $editingCashFlow = null;

        if ($request->filled('edit')) {
            $editingCashFlow = $activeMember
                ->cashFlows()
                ->with('category')
                ->whereKey($request->integer('edit'))
                ->first();
        }

        $income = $summaryItems->where('type', 'income')->sum('amount');
        $expense = $summaryItems->where('type', 'expense')->sum('amount');
        $mustSaving = $this->moneyTracker->mustSavingTotal($summaryItems->where('type', 'expense')->values());

        return view('cash-flows.index', [
            'cashFlows' => $cashFlows,
            'editingCashFlow' => $editingCashFlow,
            'categories' => $activeMember->categories()->orderBy('name')->get(),
            'income' => $income,
            'expense' => $expense,
            'net' => $income - $expense,
            'mustSaving' => $mustSaving,
            'filters' => array_merge($request->only(['month', 'type', 'category_id', 'search']), [
                'month' => $period['month'] ?? $month,
            ]),
            'activeMember' => $activeMember,
            'period' => $period,
        ]);
    }

    public function store(StoreCashFlowRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($data['type'] === 'income') {
            $data['category_id'] = null;
        }

        $request->user()->cashFlows()->create($data);

        return back()->with('status', 'Transaksi berhasil dibuat.');
    }

    public function update(UpdateCashFlowRequest $request, CashFlow $cashFlow): RedirectResponse
    {
        abort_unless($cashFlow->user_id === $request->user()->id, 404);

        $data = $request->validated();

        if ($data['type'] === 'income') {
            $data['category_id'] = null;
        }

        $cashFlow->update($data);

        return to_route('cash-flows.index')->with('status', 'Transaksi berhasil diupdate.');
    }

    public function destroy(Request $request, CashFlow $cashFlow): RedirectResponse
    {
        abort_unless($cashFlow->user_id === $request->user()->id, 404);

        $cashFlow->delete();

        return back()->with('status', 'Transaksi berhasil dihapus.');
    }
}
