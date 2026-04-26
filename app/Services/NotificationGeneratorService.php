<?php

namespace App\Services;

use App\Models\AppNotification;
use App\Models\RecurringTransaction;
use App\Models\Transaction;
use App\Models\User;
use Carbon\CarbonInterface;

class NotificationGeneratorService
{
    public const INACTIVITY_DAYS = 7;

    public function __construct(
        protected BudgetHealthService $budgetHealthService
    ) {
    }

    public function generateRecurringDueSoonNotifications(?CarbonInterface $date = null): int
    {
        $date ??= now();
        $createdCount = 0;

        $recurringTransactions = RecurringTransaction::query()
            ->with(['user', 'account', 'category'])
            ->where('is_active', true)
            ->whereNotNull('next_due_date')
            ->get();

        foreach ($recurringTransactions as $recurringTransaction) {
            if (! $recurringTransaction->isDueSoon($date)) {
                continue;
            }

            $daysUntilDue = $date->copy()->startOfDay()->diffInDays($recurringTransaction->next_due_date->copy()->startOfDay(), false);
            $title = $daysUntilDue <= 0
                ? sprintf('%s is due today', $recurringTransaction->description)
                : sprintf('%s is due in %d day%s', $recurringTransaction->description, $daysUntilDue, $daysUntilDue === 1 ? '' : 's');

            $message = sprintf(
                '%s of %s is scheduled on %s from %s.',
                ucfirst($recurringTransaction->type),
                $this->currency((float) $recurringTransaction->amount),
                $recurringTransaction->next_due_date->format('M d, Y'),
                $recurringTransaction->account->name
            );

            $createdCount += $this->createUniqueNotification(
                user: $recurringTransaction->user,
                type: 'recurring_due_soon',
                fingerprint: sprintf('recurring_due_soon:%d:%s', $recurringTransaction->id, $recurringTransaction->next_due_date->toDateString()),
                title: $title,
                message: $message,
                actionUrl: route('recurring-transactions.index'),
                data: [
                    'recurring_transaction_id' => $recurringTransaction->id,
                    'next_due_date' => $recurringTransaction->next_due_date->toDateString(),
                ],
            );
        }

        return $createdCount;
    }

    public function generateBudgetNotifications(?CarbonInterface $date = null): int
    {
        $date ??= now();
        $createdCount = 0;

        User::query()->lazy()->each(function (User $user) use ($date, &$createdCount) {
            $summaries = $this->budgetHealthService->summariesForUser($user, $date);

            foreach ($summaries as $summary) {
                if (! in_array($summary['status'], ['nearing', 'exceeded'], true)) {
                    continue;
                }

                $type = $summary['status'] === 'exceeded'
                    ? 'budget_exceeded'
                    : 'budget_nearing_limit';

                $title = $summary['status'] === 'exceeded'
                    ? sprintf('%s budget exceeded', $summary['category']->name)
                    : sprintf('%s budget is at %.0f%%', $summary['category']->name, $summary['usage_percentage']);

                $message = $summary['status'] === 'exceeded'
                    ? sprintf('You are over budget by %s this month.', $this->currency($summary['over_by']))
                    : sprintf('You have spent %s of your %s budget.', $this->currency($summary['spent']), $this->currency($summary['amount']));

                $createdCount += $this->createUniqueNotification(
                    user: $user,
                    type: $type,
                    fingerprint: sprintf('%s:%d:%s-%s', $type, $summary['category']->id, $date->year, str_pad((string) $date->month, 2, '0', STR_PAD_LEFT)),
                    title: $title,
                    message: $message,
                    actionUrl: route('budgets.index', ['year' => $date->year, 'month' => $date->month]),
                    data: [
                        'category_id' => $summary['category']->id,
                        'year' => $date->year,
                        'month' => $date->month,
                    ],
                );
            }
        });

        return $createdCount;
    }

    public function generateInactivityNotifications(?CarbonInterface $date = null): int
    {
        $date ??= now();
        $createdCount = 0;
        $threshold = $date->copy()->subDays(self::INACTIVITY_DAYS);

        User::query()->lazy()->each(function (User $user) use ($date, $threshold, &$createdCount) {
            if ($user->created_at !== null && $user->created_at->gt($threshold)) {
                return;
            }

            $lastTransaction = Transaction::query()
                ->where('user_id', $user->id)
                ->latest('created_at')
                ->first();

            if ($lastTransaction && $lastTransaction->created_at->gt($threshold)) {
                return;
            }

            $daysSince = $lastTransaction
                ? $lastTransaction->created_at->diffInDays($date)
                : $user->created_at?->diffInDays($date) ?? self::INACTIVITY_DAYS;

            $createdCount += $this->createUniqueNotification(
                user: $user,
                type: 'inactive_logging',
                fingerprint: sprintf('inactive_logging:%d:%s', $user->id, $date->copy()->startOfWeek()->toDateString()),
                title: 'You have not logged a transaction recently',
                message: sprintf('It has been %d day%s since your last recorded activity.', $daysSince, $daysSince === 1 ? '' : 's'),
                actionUrl: route('transactions.create'),
                data: [
                    'days_since' => $daysSince,
                ],
            );
        });

        return $createdCount;
    }

    public function generateMonthEndSummaryNotifications(?CarbonInterface $date = null): int
    {
        $date ??= now();

        if ((int) $date->day !== 1) {
            return 0;
        }

        $period = $date->copy()->subMonth();
        $createdCount = 0;

        User::query()->lazy()->each(function (User $user) use ($period, &$createdCount) {
            $income = (float) Transaction::query()
                ->where('user_id', $user->id)
                ->where('type', 'income')
                ->whereYear('transaction_date', $period->year)
                ->whereMonth('transaction_date', $period->month)
                ->sum('amount');

            $expense = (float) Transaction::query()
                ->where('user_id', $user->id)
                ->where('type', 'expense')
                ->whereYear('transaction_date', $period->year)
                ->whereMonth('transaction_date', $period->month)
                ->sum('amount');

            if ($income === 0.0 && $expense === 0.0) {
                return;
            }

            $createdCount += $this->createUniqueNotification(
                user: $user,
                type: 'month_end_summary_ready',
                fingerprint: sprintf('month_end_summary_ready:%d:%s-%s', $user->id, $period->year, str_pad((string) $period->month, 2, '0', STR_PAD_LEFT)),
                title: sprintf('%s summary is ready', $period->format('F Y')),
                message: sprintf(
                    'You closed the month with %s income and %s expenses.',
                    $this->currency($income),
                    $this->currency($expense)
                ),
                actionUrl: route('dashboard'),
                data: [
                    'year' => $period->year,
                    'month' => $period->month,
                    'income' => $income,
                    'expense' => $expense,
                ],
            );
        });

        return $createdCount;
    }

    protected function createUniqueNotification(
        User $user,
        string $type,
        string $fingerprint,
        string $title,
        string $message,
        ?string $actionUrl = null,
        array $data = []
    ): int {
        $notification = AppNotification::query()->firstOrCreate(
            ['fingerprint' => $fingerprint],
            [
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'action_url' => $actionUrl,
                'data' => $data,
            ]
        );

        return $notification->wasRecentlyCreated ? 1 : 0;
    }

    protected function currency(float $amount): string
    {
        return 'P'.number_format($amount, 2);
    }
}
