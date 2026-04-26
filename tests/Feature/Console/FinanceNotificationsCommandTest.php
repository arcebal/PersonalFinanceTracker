<?php

namespace Tests\Feature\Console;

use App\Models\Account;
use App\Models\Budget;
use App\Models\Category;
use App\Models\RecurringTransaction;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinanceNotificationsCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_recurring_reminder_command_creates_a_due_soon_notification_without_duplicates(): void
    {
        Carbon::setTestNow('2026-04-20 08:00:00');

        $user = User::factory()->create();
        [$account, $expenseCategory] = $this->seedExpenseWorkspace($user);

        RecurringTransaction::create([
            'user_id' => $user->id,
            'account_id' => $account->id,
            'category_id' => $expenseCategory->id,
            'type' => 'expense',
            'amount' => 950,
            'description' => 'Water bill',
            'frequency' => 'monthly',
            'interval' => 1,
            'reminder_days_before' => 3,
            'start_date' => '2026-04-22',
            'next_due_date' => '2026-04-22',
            'is_active' => true,
        ]);

        $this->artisan('finance:generate-recurring-reminders')->assertExitCode(0);
        $this->artisan('finance:generate-recurring-reminders')->assertExitCode(0);

        $this->assertDatabaseCount('app_notifications', 1);
        $this->assertDatabaseHas('app_notifications', [
            'user_id' => $user->id,
            'type' => 'recurring_due_soon',
            'title' => 'Water bill is due in 2 days',
        ]);
    }

    public function test_budget_notification_command_creates_nearing_and_exceeded_alerts(): void
    {
        Carbon::setTestNow('2026-04-20 08:00:00');

        $user = User::factory()->create();
        [$account, $foodCategory] = $this->seedExpenseWorkspace($user, 'Food');
        [, $travelCategory] = $this->seedExpenseWorkspace($user, 'Travel', createAccount: false);

        Budget::create([
            'user_id' => $user->id,
            'category_id' => $foodCategory->id,
            'year' => 2026,
            'month' => 4,
            'amount' => 1000,
        ]);

        Budget::create([
            'user_id' => $user->id,
            'category_id' => $travelCategory->id,
            'year' => 2026,
            'month' => 4,
            'amount' => 1000,
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'account_id' => $account->id,
            'category_id' => $foodCategory->id,
            'type' => 'expense',
            'amount' => 850,
            'description' => 'Groceries',
            'transaction_date' => '2026-04-18',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'account_id' => $account->id,
            'category_id' => $travelCategory->id,
            'type' => 'expense',
            'amount' => 1250,
            'description' => 'Flights',
            'transaction_date' => '2026-04-19',
        ]);

        $this->artisan('finance:generate-budget-notifications')->assertExitCode(0);

        $this->assertDatabaseHas('app_notifications', [
            'user_id' => $user->id,
            'type' => 'budget_nearing_limit',
            'title' => 'Food budget is at 85%',
        ]);

        $this->assertDatabaseHas('app_notifications', [
            'user_id' => $user->id,
            'type' => 'budget_exceeded',
            'title' => 'Travel budget exceeded',
        ]);
    }

    public function test_inactivity_notification_command_creates_a_weekly_reminder_for_stale_users(): void
    {
        Carbon::setTestNow('2026-04-20 09:00:00');

        $user = User::factory()->create([
            'created_at' => now()->subDays(20),
            'updated_at' => now()->subDays(20),
        ]);

        $this->artisan('finance:generate-inactivity-notifications')->assertExitCode(0);
        $this->artisan('finance:generate-inactivity-notifications')->assertExitCode(0);

        $this->assertDatabaseCount('app_notifications', 1);
        $this->assertDatabaseHas('app_notifications', [
            'user_id' => $user->id,
            'type' => 'inactive_logging',
        ]);
    }

    public function test_month_end_summary_command_creates_a_previous_month_summary_on_the_first_day_of_the_month(): void
    {
        Carbon::setTestNow('2026-05-01 09:15:00');

        $user = User::factory()->create();
        [$account, $expenseCategory] = $this->seedExpenseWorkspace($user);

        $incomeCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Salary',
            'type' => 'income',
            'color' => '#16a34a',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'account_id' => $account->id,
            'category_id' => $incomeCategory->id,
            'type' => 'income',
            'amount' => 30000,
            'description' => 'Salary',
            'transaction_date' => '2026-04-05',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'account_id' => $account->id,
            'category_id' => $expenseCategory->id,
            'type' => 'expense',
            'amount' => 7000,
            'description' => 'Household',
            'transaction_date' => '2026-04-16',
        ]);

        $this->artisan('finance:generate-month-end-summaries')->assertExitCode(0);

        $this->assertDatabaseHas('app_notifications', [
            'user_id' => $user->id,
            'type' => 'month_end_summary_ready',
            'title' => 'April 2026 summary is ready',
        ]);
    }

    protected function seedExpenseWorkspace(User $user, string $categoryName = 'Utilities', bool $createAccount = true): array
    {
        $account = $createAccount
            ? Account::create([
                'user_id' => $user->id,
                'name' => 'Main Wallet',
                'type' => 'cash',
                'balance' => 5000,
            ])
            : $user->accounts()->first();

        $category = Category::create([
            'user_id' => $user->id,
            'name' => $categoryName,
            'type' => 'expense',
            'color' => '#dc2626',
        ]);

        return [$account, $category];
    }
}
