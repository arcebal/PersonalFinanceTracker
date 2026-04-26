<?php

namespace App\Services;

use App\Models\RecurringTransaction;
use App\Models\Transaction;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class InsightService
{
    public function __construct(
        protected BudgetHealthService $budgetHealthService
    ) {
    }

    public function forUser(User $user, ?CarbonInterface $date = null): Collection
    {
        $date ??= now();
        $currentMonth = $date->copy()->startOfMonth();
        $previousMonth = $currentMonth->copy()->subMonth();
        $insights = collect();

        $currentExpenses = Transaction::query()
            ->selectRaw('category_id, SUM(amount) as total')
            ->where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereYear('transaction_date', $currentMonth->year)
            ->whereMonth('transaction_date', $currentMonth->month)
            ->with('category')
            ->groupBy('category_id')
            ->get()
            ->keyBy('category_id');

        $previousExpenses = Transaction::query()
            ->selectRaw('category_id, SUM(amount) as total')
            ->where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereYear('transaction_date', $previousMonth->year)
            ->whereMonth('transaction_date', $previousMonth->month)
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        $largestIncrease = $currentExpenses
            ->map(function (Transaction $transaction) use ($previousExpenses) {
                $previousTotal = (float) ($previousExpenses[$transaction->category_id] ?? 0);
                $currentTotal = (float) $transaction->total;

                if ($previousTotal <= 0 || $currentTotal <= $previousTotal) {
                    return null;
                }

                return [
                    'category' => $transaction->category,
                    'current_total' => $currentTotal,
                    'previous_total' => $previousTotal,
                    'percentage' => round((($currentTotal - $previousTotal) / $previousTotal) * 100),
                ];
            })
            ->filter()
            ->sortByDesc('percentage')
            ->first();

        if ($largestIncrease) {
            $insights->push([
                'type' => 'trend',
                'headline' => sprintf('%s spending is up %d%% this month', $largestIncrease['category']->name, $largestIncrease['percentage']),
                'body' => sprintf(
                    'You have spent %s more than last month in this category.',
                    $this->currency($largestIncrease['current_total'] - $largestIncrease['previous_total'])
                ),
                'action_url' => route('transactions.index', ['category_id' => $largestIncrease['category']->id, 'type' => 'expense']),
            ]);
        }

        $exceededBudget = $this->budgetHealthService
            ->summariesForUser($user, $currentMonth)
            ->firstWhere('status', 'exceeded');

        if ($exceededBudget) {
            $insights->push([
                'type' => 'budget',
                'headline' => sprintf('%s exceeded budget by %s', $exceededBudget['category']->name, $this->currency($exceededBudget['over_by'])),
                'body' => sprintf(
                    'Budgeted %s and spent %s so far this month.',
                    $this->currency($exceededBudget['amount']),
                    $this->currency($exceededBudget['spent'])
                ),
                'action_url' => route('budgets.index', ['year' => $currentMonth->year, 'month' => $currentMonth->month]),
            ]);
        }

        $currentIncome = (float) Transaction::query()
            ->where('user_id', $user->id)
            ->where('type', 'income')
            ->whereYear('transaction_date', $currentMonth->year)
            ->whereMonth('transaction_date', $currentMonth->month)
            ->sum('amount');

        $previousIncome = (float) Transaction::query()
            ->where('user_id', $user->id)
            ->where('type', 'income')
            ->whereYear('transaction_date', $previousMonth->year)
            ->whereMonth('transaction_date', $previousMonth->month)
            ->sum('amount');

        if ($previousIncome > 0 && $currentIncome < $previousIncome) {
            $insights->push([
                'type' => 'income',
                'headline' => sprintf('Income is lower by %s vs last month', $this->currency($previousIncome - $currentIncome)),
                'body' => sprintf(
                    'This month recorded %s compared with %s last month.',
                    $this->currency($currentIncome),
                    $this->currency($previousIncome)
                ),
                'action_url' => route('transactions.index', ['type' => 'income']),
            ]);
        }

        $topExpense = $currentExpenses->sortByDesc(function (Transaction $transaction) {
            return (float) $transaction->total;
        })->first();

        if ($topExpense) {
            $insights->push([
                'type' => 'category',
                'headline' => sprintf('%s is your top expense category this month', $topExpense->category->name),
                'body' => sprintf('Current spend in this category is %s.', $this->currency((float) $topExpense->total)),
                'action_url' => route('transactions.index', ['category_id' => $topExpense->category_id, 'type' => 'expense']),
            ]);
        }

        $dueRecurringCount = RecurringTransaction::query()
            ->where('user_id', $user->id)
            ->where('type', 'expense')
            ->where('is_active', true)
            ->whereNotNull('next_due_date')
            ->whereBetween('next_due_date', [$date->copy()->startOfDay(), $date->copy()->addDays(7)->endOfDay()])
            ->count();

        if ($dueRecurringCount > 0) {
            $insights->push([
                'type' => 'recurring',
                'headline' => sprintf('%d recurring expense%s due this week', $dueRecurringCount, $dueRecurringCount === 1 ? ' is' : 's are'),
                'body' => 'Review upcoming bills and confirm them when they become due.',
                'action_url' => route('recurring-transactions.index'),
            ]);
        }

        return $insights->take(5)->values();
    }

    protected function currency(float $amount): string
    {
        return 'P'.number_format($amount, 2);
    }
}
