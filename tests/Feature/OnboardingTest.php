<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Category;
use App\Models\User;
use App\Services\OnboardingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnboardingTest extends TestCase
{
    use RefreshDatabase;

    public function test_incomplete_users_are_redirected_from_the_app_to_onboarding(): void
    {
        $user = User::factory()->onboardingPending()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect(route('onboarding.start', absolute: false));
    }

    public function test_onboarding_resumes_at_the_saved_step(): void
    {
        $user = User::factory()->onboardingPending()->create([
            'onboarding_step' => OnboardingService::STEP_BUDGET,
        ]);

        $response = $this->actingAs($user)->get('/onboarding');

        $response->assertRedirect(route('onboarding.budget.show', absolute: false));
    }

    public function test_users_cannot_open_later_onboarding_steps_out_of_order(): void
    {
        $user = User::factory()->onboardingPending()->create();

        $response = $this->actingAs($user)->get('/onboarding/transaction');

        $response->assertRedirect(route('onboarding.account.show', absolute: false));
    }

    public function test_required_steps_can_be_completed_and_optional_steps_skipped(): void
    {
        $user = User::factory()->onboardingPending()->create();

        $this->actingAs($user)
            ->post('/onboarding/account', [
                'name' => 'Main Wallet',
                'type' => 'cash',
                'balance' => '500.00',
                'description' => 'Primary spending wallet',
            ])
            ->assertRedirect(route('onboarding.categories.show', absolute: false));

        $this->assertDatabaseHas('accounts', [
            'user_id' => $user->id,
            'name' => 'Main Wallet',
        ]);

        $this->actingAs($user)
            ->post('/onboarding/categories', [
                'category_ids' => ['salary-income', 'food-expense', 'transport-expense'],
            ])
            ->assertRedirect(route('onboarding.budget.show', absolute: false));

        $this->assertDatabaseHas('categories', [
            'user_id' => $user->id,
            'name' => 'Food & Dining',
            'type' => 'expense',
        ]);

        $this->actingAs($user)
            ->post('/onboarding/budget/skip')
            ->assertRedirect(route('onboarding.transaction.show', absolute: false));

        $this->actingAs($user)
            ->post('/onboarding/transaction/skip')
            ->assertRedirect(route('dashboard', absolute: false));

        $user->refresh();

        $this->assertNotNull($user->onboarding_completed_at);
        $this->assertNull($user->onboarding_step);
        $this->actingAs($user)->get('/dashboard')->assertOk();
    }

    public function test_budget_step_saves_positive_expense_budgets_only(): void
    {
        $user = User::factory()->onboardingPending()->create([
            'onboarding_step' => OnboardingService::STEP_BUDGET,
        ]);

        $groceries = Category::create([
            'user_id' => $user->id,
            'name' => 'Groceries',
            'type' => 'expense',
            'color' => '#84cc16',
        ]);

        $transport = Category::create([
            'user_id' => $user->id,
            'name' => 'Transport',
            'type' => 'expense',
            'color' => '#7c3aed',
        ]);

        $response = $this->actingAs($user)->post('/onboarding/budget', [
            'amounts' => [
                $groceries->id => '2500',
                $transport->id => '0',
            ],
        ]);

        $response->assertRedirect(route('onboarding.transaction.show', absolute: false));
        $this->assertDatabaseHas('budgets', [
            'user_id' => $user->id,
            'category_id' => $groceries->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => '2500.00',
        ]);
        $this->assertDatabaseMissing('budgets', [
            'user_id' => $user->id,
            'category_id' => $transport->id,
            'year' => now()->year,
            'month' => now()->month,
        ]);
    }

    public function test_transaction_step_records_the_first_transaction_and_completes_onboarding(): void
    {
        $user = User::factory()->onboardingPending()->create([
            'onboarding_step' => OnboardingService::STEP_TRANSACTION,
        ]);

        $account = Account::create([
            'user_id' => $user->id,
            'name' => 'Main Wallet',
            'type' => 'cash',
            'balance' => 100,
            'description' => null,
        ]);

        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Salary & Income',
            'type' => 'income',
            'color' => '#16a34a',
        ]);

        $response = $this->actingAs($user)->post('/onboarding/transaction', [
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'income',
            'amount' => '50.00',
            'description' => 'Opening salary',
            'transaction_date' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'income',
            'amount' => '50.00',
        ]);
        $this->assertSame(150.0, (float) $account->fresh()->balance);
        $this->assertNotNull($user->fresh()->onboarding_completed_at);
    }

    public function test_completed_users_are_redirected_away_from_onboarding(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/onboarding');

        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
