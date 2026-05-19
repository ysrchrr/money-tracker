<?php

namespace App\Http\Controllers;

use App\Support\MoneyTrackerService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected MoneyTrackerService $moneyTracker,
    ) {}

    public function index(Request $request): View
    {
        $activeMember = $this->activeMember($request);
        $period = $this->moneyTracker->periodForMonth($request->string('month')->toString() ?: null, $activeMember);

        $cashFlows = $activeMember
            ->cashFlows()
            ->with('category')
            ->whereBetween('transaction_date', [$period['start']->toDateString(), $period['end']->toDateString()])
            ->latest('transaction_date')
            ->latest('id')
            ->get();

        $income = $cashFlows->where('type', 'income')->sum('amount');
        $expense = $cashFlows->where('type', 'expense')->sum('amount');
        $mustSaving = $this->moneyTracker->mustSavingTotal($cashFlows->where('type', 'expense')->values());

        $categorySpend = $cashFlows
            ->where('type', 'expense')
            ->groupBy(fn ($cashFlow) => $cashFlow->category?->name ?? 'No Category')
            ->map(function ($items, $name) {
                $amount = $items->sum('amount');

                return [
                    'name' => $name,
                    'amount' => $amount,
                    'percentage' => optional($items->first()->category)->percentage ?? 0,
                ];
            })
            ->sortByDesc('amount')
            ->values();

        return view('dashboard', [
            'period' => $period,
            'activeMember' => $activeMember,
            'income' => $income,
            'expense' => $expense,
            'net' => $income - $expense,
            'mustSaving' => $mustSaving,
            'recentTransactions' => $cashFlows->take(8),
            'categorySpend' => $categorySpend,
        ]);
    }
}
