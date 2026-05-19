<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashFlow;
use App\Models\User;
use App\Support\MoneyTrackerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function __construct(
        protected MoneyTrackerService $moneyTracker,
    ) {}

    public function index(Request $request): View
    {
        $members = User::query()
            ->where('role', 'member')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%');
                });
            })
            ->withCount(['cashFlows', 'categories'])
            ->withSum(['cashFlows as total_income' => function ($query) {
                $query->where('type', 'income');
            }], 'amount')
            ->withSum(['cashFlows as total_expense' => function ($query) {
                $query->where('type', 'expense');
            }], 'amount')
            ->withMax('cashFlows as last_transaction_date', 'transaction_date')
            ->orderBy('name')
            ->get();

        return view('admin.members.index', [
            'members' => $members,
            'filters' => $request->only(['search']),
        ]);
    }

    public function show(User $user): View
    {
        abort_unless($user->role === 'member', 404);

        $cashFlows = $user->cashFlows()
            ->with('category')
            ->latest('transaction_date')
            ->latest('id')
            ->limit(20)
            ->get();

        $income = $user->cashFlows()->where('type', 'income')->sum('amount');
        $expense = $user->cashFlows()->where('type', 'expense')->sum('amount');
        $mustSaving = $this->moneyTracker->mustSavingTotal(
            CashFlow::query()
                ->where('user_id', $user->id)
                ->where('type', 'expense')
                ->with('category')
                ->get(),
        );

        return view('admin.members.show', [
            'member' => $user,
            'cashFlows' => $cashFlows,
            'income' => $income,
            'expense' => $expense,
            'net' => $income - $expense,
            'mustSaving' => $mustSaving,
            'categoriesCount' => $user->categories()->count(),
            'transactionsCount' => $user->cashFlows()->count(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role === 'member', 404);

        $validated = $request->validate([
            'member_type' => ['required', Rule::in(['silver', 'gold'])],
        ]);

        $user->update([
            'member_type' => $validated['member_type'],
        ]);

        return back()->with('status', 'Status member '.$user->name.' berhasil diupdate ke '.strtoupper($validated['member_type']).'.');
    }
}
