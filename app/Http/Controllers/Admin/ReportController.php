<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashFlow;
use App\Support\MoneyTrackerService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        protected MoneyTrackerService $moneyTracker,
    ) {}

    public function index(Request $request): View
    {
        $period = $this->moneyTracker->periodForMonth($request->string('month')->toString() ?: null);

        $cashFlows = CashFlow::query()
            ->with(['user', 'category'])
            ->whereBetween('transaction_date', [$period['start']->toDateString(), $period['end']->toDateString()])
            ->get();

        $income = $cashFlows->where('type', 'income')->sum('amount');
        $expense = $cashFlows->where('type', 'expense')->sum('amount');

        $memberComparison = $cashFlows
            ->groupBy('user_id')
            ->map(function ($items) {
                $income = $items->where('type', 'income')->sum('amount');
                $expense = $items->where('type', 'expense')->sum('amount');

                return [
                    'user' => $items->first()->user,
                    'income' => $income,
                    'expense' => $expense,
                    'net' => $income - $expense,
                    'transactions' => $items->count(),
                ];
            })
            ->sortByDesc('expense')
            ->values();

        $categorySpend = $cashFlows
            ->where('type', 'expense')
            ->groupBy(fn ($cashFlow) => $cashFlow->category?->name ?? 'No Category')
            ->map(function ($items, $name) use ($expense) {
                return [
                    'name' => $name,
                    'amount' => $items->sum('amount'),
                    'transactions' => $items->count(),
                    'share' => $expense > 0 ? round(($items->sum('amount') / $expense) * 100) : 0,
                ];
            })
            ->sortByDesc('amount')
            ->values();

        return view('admin.reports.index', [
            'period' => $period,
            'income' => $income,
            'expense' => $expense,
            'net' => $income - $expense,
            'mustSaving' => $this->moneyTracker->mustSavingTotal($cashFlows->where('type', 'expense')->values()),
            'memberComparison' => $memberComparison,
            'categorySpend' => $categorySpend,
        ]);
    }
}
