<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashFlow;
use App\Models\User;
use App\Support\MoneyTrackerService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function __construct(
        protected MoneyTrackerService $moneyTracker,
    ) {}

    public function index(Request $request): View
    {
        $query = CashFlow::query()->with(['user', 'category']);

        if ($request->filled('month')) {
            $query->whereMonth('transaction_date', substr($request->string('month')->toString(), 5, 2))
                ->whereYear('transaction_date', substr($request->string('month')->toString(), 0, 4));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->string('type')->toString());
        }

        if ($request->filled('member_id')) {
            $query->where('user_id', $request->integer('member_id'));
        }

        if ($request->filled('search')) {
            $query->where(function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where('description', 'like', '%'.$search.'%')
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('name', 'like', '%'.$search.'%')
                            ->orWhere('email', 'like', '%'.$search.'%');
                    });
            });
        }

        $summaryItems = (clone $query)->get();
        $cashFlows = $query
            ->latest('transaction_date')
            ->latest('id')
            ->get();

        $income = $summaryItems->where('type', 'income')->sum('amount');
        $expense = $summaryItems->where('type', 'expense')->sum('amount');
        $mustSaving = $this->moneyTracker->mustSavingTotal($summaryItems->where('type', 'expense')->values());

        return view('admin.transactions.index', [
            'cashFlows' => $cashFlows,
            'members' => User::query()->where('role', 'member')->orderBy('name')->get(),
            'income' => $income,
            'expense' => $expense,
            'net' => $income - $expense,
            'mustSaving' => $mustSaving,
            'filters' => $request->only(['month', 'type', 'member_id', 'search']),
        ]);
    }
}
