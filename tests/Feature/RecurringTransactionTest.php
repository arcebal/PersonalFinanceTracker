<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Category;
use App\Models\RecurringTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecurringTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_a_recurring_transaction(): void
    {
        $user = User::factory()->create();
        [$account, $incomeCategory, $expenseCategory] = $this->seedFinanceWorkspace($user);

        $response = $this
            ->actingAs($user)
            ->post(route('recurring-transactions.store'), [
                'account_id' => $account->id,
                'category_id' => $expenseCategory->id,
                'type' => 'expense',
                'amount' => 1450.50,
                'description' => 'Apartment rent',
                'frequency' => 'monthly',
                'interval' => 1,
                'reminder_days_before' => 3,
                'start_date' => '2026-05-01',
                'next_due_date' => '2026-05-01',
                'ends_on' => null,
                'is_active' => '1',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('recurring-transactions.index'));

        $this->assertDatabaseHas('recurring_transactions', [
            'user_id' => $user->id,
            'description' => 'Apartment rent',
            'type' => 'expense',
            'frequency' => 'monthly',
            'interval' => 1,
            'reminder_days_before' => 3,
        ]);

        $this->assertSame(
            '2026-05-01',
            RecurringTransaction::query()->where('user_id', $user->id)->firstOrFail()->next_due_date?->toDateString()
        );
    }

    public function test_confirming_a_due_recurring_transaction_creates_a_transaction_and_advances_the_schedule(): void
    {
        $user = User::factory()->create();
        [$account, $incomeCategory, $expenseCategory] = $this->seedFinanceWorkspace($user, 6000);

        $recurringTransaction = RecurringTransaction::create([
            'user_id' => $user->id,
            'account_id' => $account->id,
            'category_id' => $expenseCategory->id,
            'type' => 'expense',
            'amount' => 1800,
            'description' => 'Apartment rent',
            'frequency' => 'monthly',
            'interval' => 1,
            'reminder_days_before' => 3,
            'start_date' => '2026-04-01',
            'next_due_date' => '2026-04-01',
            'is_active' => true,
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('recurring-transactions.confirm', $recurringTransaction));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('transactions.index'));

        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'account_id' => $account->id,
            'category_id' => $expenseCategory->id,
            'description' => 'Apartment rent',
            'transaction_date' => '2026-04-01',
        ]);

        $account->refresh();
        $recurringTransaction->refresh();

        $this->assertSame(4200.0, (float) $account->balance);
        $this->assertSame('2026-05-01', $recurringTransaction->next_due_date?->toDateString());
        $this->assertNotNull($recurringTransaction->last_processed_at);
    }

    public function test_confirming_a_due_expense_recurring_transaction_requires_sufficient_balance(): void
    {
        $user = User::factory()->create();
        [$account, $incomeCategory, $expenseCategory] = $this->seedFinanceWorkspace($user, 500);

        $recurringTransaction = RecurringTransaction::create([
            'user_id' => $user->id,
            'account_id' => $account->id,
            'category_id' => $expenseCategory->id,
            'type' => 'expense',
            'amount' => 1000,
            'description' => 'Laptop installment',
            'frequency' => 'monthly',
            'interval' => 1,
            'reminder_days_before' => 3,
            'start_date' => '2026-04-01',
            'next_due_date' => '2026-04-01',
            'is_active' => true,
        ]);

        $response = $this
            ->actingAs($user)
            ->from(route('recurring-transactions.index'))
            ->post(route('recurring-transactions.confirm', $recurringTransaction));

        $response
            ->assertRedirect(route('recurring-transactions.index'))
            ->assertSessionHasErrors('recurring_transaction');

        $this->assertDatabaseMissing('transactions', [
            'user_id' => $user->id,
            'description' => 'Laptop installment',
        ]);
    }

    protected function seedFinanceWorkspace(User $user, float $balance = 2500): array
    {
        $account = Account::create([
            'user_id' => $user->id,
            'name' => 'Wallet',
            'type' => 'cash',
            'balance' => $balance,
        ]);

        $incomeCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Salary',
            'type' => 'income',
            'color' => '#16a34a',
        ]);

        $expenseCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Housing',
            'type' => 'expense',
            'color' => '#dc2626',
        ]);

        return [$account, $incomeCategory, $expenseCategory];
    }
}
