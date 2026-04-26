<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\Transaction;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class BudgetHealthService
{
    public const NEARING_LIMIT_PERCENTAGE = 80;

    public function summariesForUser(User $user, ?CarbonInterface $month = null): Collection
    {
        $month ??= now();

        $budgets = Budget::query()
            ->where('user_id', $user->id)
            ->where('year', $month->year)
            ->where('month', $month->month)
            ->where('amount', '>', 0)
            ->whereHas('category', function ($query) {
                $query->where('type', 'expense');
            })
            ->with('category')
            ->get();

        if ($budgets->isEmpty()) {
            return collect();
        }

        $spendingByCategory = Transaction::query()
            ->selectRaw('category_id, SUM(amount) as spent_total')
            ->where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereYear('transaction_date', $month->year)
            ->whereMonth('transaction_date', $month->month)
            ->groupBy('category_id')
            ->pluck('spent_total', 'category_id');

        return $budgets->map(function (Budget $budget) use ($spendingByCategory) {
            $spent = (float) ($spendingByCategory[$budget->category_id] ?? 0);
            $amount = (float) $budget->amount;
            $usagePercentage = $amount > 0
                ? round(($spent / $amount) * 100, 2)
                : 0.0;

            $status = 'safe';

            if ($spent > $amount) {
                $status = 'exceeded';
            } elseif ($usagePercentage >= self::NEARING_LIMIT_PERCENTAGE) {
                $status = 'nearing';
            }

            return [
                'budget' => $budget,
                'category' => $budget->category,
                'amount' => $amount,
                'spent' => $spent,
                'remaining' => max($amount - $spent, 0),
                'over_by' => max($spent - $amount, 0),
                'usage_percentage' => $usagePercentage,
                'status' => $status,
            ];
        })->sortByDesc(function (array $summary) {
            return [$summary['status'] === 'exceeded', $summary['usage_percentage']];
        })->values();
    }
}
