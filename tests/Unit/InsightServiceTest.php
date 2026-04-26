<?php

namespace Tests\Unit;

use App\Models\Account;
use App\Models\Budget;
use App\Models\Category;
use App\Models\RecurringTransaction;
use App\Models\Transaction;
use App\Models\User;
use App\Services\InsightService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InsightServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_it_generates_rule_based_insights_from_finance_activity(): void
    {
        Carbon::setTestNow('2026-05-15 08:00:00');

        $user = User::factory()->create();
        $account = Account::create([
            'user_id' => $user->id,
            'name' => 'Wallet',
            'type' => 'cash',
            'balance' => 10000,
        ]);

        $salaryCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Salary',
            'type' => 'income',
            'color' => '#16a34a',
        ]);

        $foodCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Food',
            'type' => 'expense',
            'color' => '#dc2626',
        ]);

        $transportCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Transport',
            'type' => 'expense',
            'color' => '#2563eb',
        ]);

        Budget::create([
            'user_id' => $user->id,
            'category_id' => $transportCategory->id,
            'year' => 2026,
            'month' => 5,
            'amount' => 1000,
        ]);

        Transaction::insert([
            [
                'user_id' => $user->id,
                'account_id' => $account->id,
                'category_id' => $foodCategory->id,
                'type' => 'expense',
                'amount' => 100,
                'description' => 'April food',
                'transaction_date' => '2026-04-08',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'account_id' => $account->id,
                'category_id' => $foodCategory->id,
                'type' => 'expense',
                'amount' => 180,
                'description' => 'May food',
                'transaction_date' => '2026-05-08',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'account_id' => $account->id,
                'category_id' => $transportCategory->id,
                'type' => 'expense',
                'amount' => 1200,
                'description' => 'May transport',
                'transaction_date' => '2026-05-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'account_id' => $account->id,
                'category_id' => $salaryCategory->id,
                'type' => 'income',
                'amount' => 5000,
                'description' => 'May salary',
                'transaction_date' => '2026-05-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'account_id' => $account->id,
                'category_id' => $salaryCategory->id,
                'type' => 'income',
                'amount' => 9000,
                'description' => 'April salary',
                'transaction_date' => '2026-04-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        RecurringTransaction::create([
            'user_id' => $user->id,
            'account_id' => $account->id,
            'category_id' => $transportCategory->id,
            'type' => 'expense',
            'amount' => 400,
            'description' => 'Weekly commute pass',
            'frequency' => 'weekly',
            'interval' => 1,
            'reminder_days_before' => 3,
            'start_date' => '2026-05-16',
            'next_due_date' => '2026-05-16',
            'is_active' => true,
        ]);

        $insights = app(InsightService::class)->forUser($user, now());

        $this->assertCount(5, $insights);
        $this->assertTrue($insights->contains(fn (array $insight) => $insight['type'] === 'trend' && str_contains($insight['headline'], 'Food spending is up')));
        $this->assertTrue($insights->contains(fn (array $insight) => $insight['type'] === 'budget' && str_contains($insight['headline'], 'Transport exceeded budget')));
        $this->assertTrue($insights->contains(fn (array $insight) => $insight['type'] === 'income' && str_contains($insight['headline'], 'Income is lower')));
        $this->assertTrue($insights->contains(fn (array $insight) => $insight['type'] === 'category' && str_contains($insight['headline'], 'Transport is your top expense category')));
        $this->assertTrue($insights->contains(fn (array $insight) => $insight['type'] === 'recurring' && str_contains($insight['headline'], '1 recurring expense is due this week')));
    }
}
