<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\Budget;
use App\Models\RecurringTransaction;
use App\Models\Transaction;
use Carbon\Carbon;

class SidebarBadgeService
{
    public static function getCounts(): array
    {
        $user = Auth::user();

        $badges = [
            'dashboard'    => ['count' => 0, 'type' => 'info',    'hidden' => true],
            'accounts'     => ['count' => 0, 'type' => 'info',    'hidden' => true],
            'transactions' => ['count' => 0, 'type' => 'info',    'hidden' => true],
            'categories'   => ['count' => 0, 'type' => 'info',    'hidden' => true],
            'budgets'      => ['count' => 0, 'type' => 'danger',  'hidden' => true],
            'recurring'    => ['count' => 0, 'type' => 'warning', 'hidden' => true],
            'profile'      => ['count' => 0, 'type' => 'info',    'hidden' => true],
        ];

        if (! $user) {
            return $badges;
        }

        try {
            $overBudget = Budget::where('user_id', $user->id)
                ->whereColumn('spent', '>', 'limit')
                ->count();

            if ($overBudget > 0) {
                $badges['budgets'] = ['count' => $overBudget, 'type' => 'danger', 'hidden' => false];
            }
        } catch (\Throwable $e) {
            // skip
        }

        try {
            $dueSoon = RecurringTransaction::where('user_id', $user->id)
                ->whereDate('next_due_date', '<=', Carbon::now()->addDays(7))
                ->whereDate('next_due_date', '>=', Carbon::now())
                ->count();

            if ($dueSoon > 0) {
                $badges['recurring'] = ['count' => $dueSoon, 'type' => 'warning', 'hidden' => false];
            }
        } catch (\Throwable $e) {
            // skip
        }

        try {
            $needsAttention = Transaction::where('user_id', $user->id)
                ->whereNull('category_id')
                ->count();

            if ($needsAttention > 0) {
                $badges['transactions'] = ['count' => $needsAttention, 'type' => 'warning', 'hidden' => false];
            }
        } catch (\Throwable $e) {
            // skip
        }

        $actionable = collect($badges)->sum('count');
        if ($actionable > 0) {
            $hasDanger  = collect($badges)->where('type', 'danger')->where('hidden', false)->count() > 0;
            $hasWarning = collect($badges)->where('type', 'warning')->where('hidden', false)->count() > 0;

            $badges['dashboard'] = [
                'count'  => $actionable,
                'type'   => $hasDanger ? 'danger' : ($hasWarning ? 'warning' : 'info'),
                'hidden' => false,
            ];
        }

        return $badges;
    }
}
