<?php

namespace App\Services;

use App\Exceptions\InsufficientBalanceException;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransactionRecorder
{
    public function record(User $user, array $attributes): Transaction
    {
        $account = Account::query()
            ->whereKey($attributes['account_id'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        Category::query()
            ->whereKey($attributes['category_id'])
            ->where('user_id', $user->id)
            ->where('type', $attributes['type'])
            ->firstOrFail();

        $amount = (float) $attributes['amount'];

        if ($attributes['type'] === 'expense' && (float) $account->balance < $amount) {
            throw new InsufficientBalanceException('Insufficient account balance.');
        }

        return DB::transaction(function () use ($user, $attributes, $account, $amount) {
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'account_id' => $account->id,
                'category_id' => $attributes['category_id'],
                'type' => $attributes['type'],
                'amount' => $amount,
                'description' => $attributes['description'] ?: null,
                'transaction_date' => $attributes['transaction_date'],
            ]);

            $account->balance = $attributes['type'] === 'income'
                ? (float) $account->balance + $amount
                : (float) $account->balance - $amount;

            $account->save();

            return $transaction->load(['account', 'category']);
        });
    }
}
