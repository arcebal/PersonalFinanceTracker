<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Category;
use App\Models\RecurringTransaction;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RecurringTransactionService
{
    public function __construct(
        protected TransactionRecorder $transactionRecorder
    ) {
    }

    public function create(User $user, array $attributes): RecurringTransaction
    {
        [$account, $category] = $this->resolveOwnedRelations($user, $attributes);

        return RecurringTransaction::create([
            'user_id' => $user->id,
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => $attributes['type'],
            'amount' => $attributes['amount'],
            'description' => $attributes['description'],
            'frequency' => $attributes['frequency'],
            'interval' => $attributes['interval'],
            'reminder_days_before' => $attributes['reminder_days_before'],
            'start_date' => $attributes['start_date'],
            'next_due_date' => $attributes['next_due_date'],
            'ends_on' => $attributes['ends_on'] ?: null,
            'is_active' => (bool) ($attributes['is_active'] ?? true),
        ]);
    }

    public function update(RecurringTransaction $recurringTransaction, array $attributes): RecurringTransaction
    {
        [$account, $category] = $this->resolveOwnedRelations($recurringTransaction->user, $attributes);

        $recurringTransaction->update([
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => $attributes['type'],
            'amount' => $attributes['amount'],
            'description' => $attributes['description'],
            'frequency' => $attributes['frequency'],
            'interval' => $attributes['interval'],
            'reminder_days_before' => $attributes['reminder_days_before'],
            'start_date' => $attributes['start_date'],
            'next_due_date' => $attributes['next_due_date'],
            'ends_on' => $attributes['ends_on'] ?: null,
            'is_active' => (bool) ($attributes['is_active'] ?? false),
        ]);

        return $recurringTransaction->fresh(['account', 'category']);
    }

    public function confirm(RecurringTransaction $recurringTransaction): void
    {
        if (! $recurringTransaction->is_active || $recurringTransaction->next_due_date === null) {
            throw ValidationException::withMessages([
                'recurring_transaction' => 'This recurring item is no longer active.',
            ]);
        }

        if (! $recurringTransaction->isDue()) {
            throw ValidationException::withMessages([
                'recurring_transaction' => 'This recurring item is not due yet.',
            ]);
        }

        $currentDueDate = $recurringTransaction->next_due_date->copy();

        DB::transaction(function () use ($recurringTransaction, $currentDueDate) {
            $this->transactionRecorder->record($recurringTransaction->user, [
                'account_id' => $recurringTransaction->account_id,
                'category_id' => $recurringTransaction->category_id,
                'type' => $recurringTransaction->type,
                'amount' => $recurringTransaction->amount,
                'description' => $recurringTransaction->description,
                'transaction_date' => $currentDueDate->toDateString(),
            ]);

            $nextDueDate = $this->calculateNextDueDate($recurringTransaction, $currentDueDate);

            $recurringTransaction->forceFill([
                'last_processed_at' => now(),
                'next_due_date' => $nextDueDate?->toDateString(),
                'is_active' => $nextDueDate !== null && $recurringTransaction->is_active,
            ])->save();

            $recurringTransaction->user->appNotifications()
                ->where('fingerprint', sprintf(
                    'recurring_due_soon:%d:%s',
                    $recurringTransaction->id,
                    $currentDueDate->toDateString()
                ))
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        });
    }

    protected function calculateNextDueDate(
        RecurringTransaction $recurringTransaction,
        CarbonInterface $fromDate
    ): ?Carbon {
        $nextDueDate = Carbon::parse($fromDate);

        if ($recurringTransaction->frequency === 'weekly') {
            $nextDueDate->addWeeks($recurringTransaction->interval);
        } else {
            $nextDueDate->addMonthsNoOverflow($recurringTransaction->interval);
        }

        if ($recurringTransaction->ends_on !== null && $nextDueDate->gt($recurringTransaction->ends_on)) {
            return null;
        }

        return $nextDueDate;
    }

    protected function resolveOwnedRelations(User $user, array $attributes): array
    {
        $account = Account::query()
            ->whereKey($attributes['account_id'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        $category = Category::query()
            ->whereKey($attributes['category_id'])
            ->where('user_id', $user->id)
            ->where('type', $attributes['type'])
            ->firstOrFail();

        return [$account, $category];
    }
}
