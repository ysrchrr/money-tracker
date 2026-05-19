<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashFlow;
use App\Models\User;
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
        $period = $this->moneyTracker->periodForMonth($request->string('month')->toString() ?: null);

        $cashFlows = CashFlow::query()
            ->with(['user', 'category'])
            ->whereBetween('transaction_date', [$period['start']->toDateString(), $period['end']->toDateString()])
            ->latest('transaction_date')
            ->latest('id')
            ->get();

        $income = $cashFlows->where('type', 'income')->sum('amount');
        $expense = $cashFlows->where('type', 'expense')->sum('amount');
        $mustSaving = $this->moneyTracker->mustSavingTotal($cashFlows->where('type', 'expense')->values());

        $memberSummary = $cashFlows
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
            ->take(8)
            ->values();

        $categorySpend = $cashFlows
            ->where('type', 'expense')
            ->groupBy(fn ($cashFlow) => $cashFlow->category?->name ?? 'No Category')
            ->map(function ($items, $name) {
                return [
                    'name' => $name,
                    'amount' => $items->sum('amount'),
                    'transactions' => $items->count(),
                ];
            })
            ->sortByDesc('amount')
            ->take(8)
            ->values();

        return view('admin.dashboard', [
            'period' => $period,
            'totalMembers' => User::query()->where('role', 'member')->count(),
            'goldMembers' => User::query()->where('role', 'member')->where('member_type', 'gold')->count(),
            'activeMembers' => $cashFlows->pluck('user_id')->unique()->count(),
            'totalTransactions' => $cashFlows->count(),
            'income' => $income,
            'expense' => $expense,
            'net' => $income - $expense,
            'mustSaving' => $mustSaving,
            'recentTransactions' => $cashFlows->take(10),
            'memberSummary' => $memberSummary,
            'categorySpend' => $categorySpend,
        ]);
    }
}
