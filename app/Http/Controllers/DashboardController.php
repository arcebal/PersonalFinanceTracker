<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Budget;
use App\Models\Category;
use App\Models\RecurringTransaction;
use App\Models\Transaction;
use App\Services\BudgetHealthService;

class DashboardController extends Controller
{
    public function __construct(
        protected BudgetHealthService $budgetHealthService,
    ) {}

    public function index()
    {
        $user = auth()->user();

        $totalBalance = Account::where('user_id', $user->id)->sum('balance');
        $totalIncome = Transaction::where('user_id', $user->id)->where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('user_id', $user->id)->where('type', 'expense')->sum('amount');
        $recentTrans = Transaction::where('user_id', $user->id)
            ->with(['category', 'account'])
            ->latest()
            ->take(5)
            ->get();

        $expenseBreakdown = Transaction::query()
            ->selectRaw('category_id, SUM(amount) as total_amount')
            ->where('user_id', $user->id)
            ->where('type', 'expense')
            ->groupBy('category_id')
            ->with('category')
            ->get()
            ->filter(fn(Transaction $transaction) => $transaction->category !== null);

        $pieLabels = $expenseBreakdown->map(fn(Transaction $transaction) => $transaction->category->name)->values();
        $pieData = $expenseBreakdown->map(fn(Transaction $transaction) => (float) $transaction->total_amount)->values();
        $pieColors = $expenseBreakdown->map(fn(Transaction $transaction) => $transaction->category->color)->values();

        $months = [];
        $income = [];
        $expense = [];
        $now = \Carbon\Carbon::now();

        for ($i = 5; $i >= 0; $i--) {
            $m = $now->copy()->subMonths($i);
            $months[] = $m->format('M');
            $start = $m->copy()->startOfMonth()->toDateString();
            $end = $m->copy()->endOfMonth()->toDateString();
            $income[] = (float) Transaction::where('user_id', $user->id)->where('type', 'income')->whereBetween('transaction_date', [$start, $end])->sum('amount');
            $expense[] = (float) Transaction::where('user_id', $user->id)->where('type', 'expense')->whereBetween('transaction_date', [$start, $end])->sum('amount');
        }

        $current = \Carbon\Carbon::now();
        $budgetSummaries = $this->budgetHealthService->summariesForUser($user, $current);
        $budgetLabels = $budgetSummaries->map(fn(array $summary) => $summary['category']->name)->values();
        $budgetAmounts = $budgetSummaries->map(fn(array $summary) => (float) $summary['amount'])->values();
        $spentAmounts = $budgetSummaries->map(fn(array $summary) => (float) $summary['spent'])->values();

        $insights = collect([]);

        $upcomingRecurringTransactions = RecurringTransaction::where('user_id', $user->id)
            ->with('account')
            ->where('is_active', true)
            ->where('next_due_date', '>=', now()->startOfDay())
            ->where('next_due_date', '<=', now()->addDays(7)->endOfDay())
            ->where(function ($query) {
                $query->whereNull('ends_on')
                    ->orWhere('ends_on', '>=', now()->startOfDay());
            })
            ->orderBy('next_due_date')
            ->get();

        return view('dashboard', compact(
            'totalBalance',
            'totalIncome',
            'totalExpense',
            'recentTrans',
            'pieLabels',
            'pieData',
            'pieColors',
            'months',
            'income',
            'expense',
            'budgetLabels',
            'budgetAmounts',
            'spentAmounts',
            'budgetSummaries',
            'insights',
            'upcomingRecurringTransactions'
        ));
    }
}
