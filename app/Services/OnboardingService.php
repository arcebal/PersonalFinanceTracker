<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class OnboardingService
{
    public const STEP_ACCOUNT = 'account';
    public const STEP_CATEGORIES = 'categories';
    public const STEP_BUDGET = 'budget';
    public const STEP_TRANSACTION = 'transaction';

    /**
     * @return array<string, array<string, mixed>>
     */
    public function steps(): array
    {
        return [
            self::STEP_ACCOUNT => [
                'title' => 'Create your first account',
                'description' => 'Set up the first cash source or e-wallet you want to track.',
                'aside' => 'Every transaction needs an account, so this is the minimum starting point for the workspace.',
                'required' => true,
            ],
            self::STEP_CATEGORIES => [
                'title' => 'Choose starter categories',
                'description' => 'Pick a starting set of income and expense categories you want the app to create for you.',
                'aside' => 'Starter categories make reports and budgeting usable immediately instead of leaving the app empty.',
                'required' => true,
            ],
            self::STEP_BUDGET => [
                'title' => 'Set a first monthly budget',
                'description' => 'Add budget targets for this month’s expense categories, or skip it for later.',
                'aside' => 'Budgets are optional during onboarding, but adding them now unlocks more useful dashboard signals right away.',
                'required' => false,
            ],
            self::STEP_TRANSACTION => [
                'title' => 'Record your first transaction',
                'description' => 'Log an initial income or expense entry, or finish onboarding and do it later.',
                'aside' => 'A first transaction gives the dashboard real activity and confirms that your setup works end to end.',
                'required' => false,
            ],
        ];
    }

    public function currentStep(User $user): string
    {
        return $user->onboarding_step ?: self::STEP_ACCOUNT;
    }

    public function ensureStarted(User $user): string
    {
        if ($user->hasCompletedOnboarding()) {
            return self::STEP_TRANSACTION;
        }

        $step = $this->currentStep($user);

        if ($user->onboarding_step !== $step) {
            $user->forceFill([
                'onboarding_step' => $step,
            ])->save();
        }

        return $step;
    }

    public function advanceTo(User $user, string $step): void
    {
        $this->assertValidStep($step);

        $user->forceFill([
            'onboarding_step' => $step,
        ])->save();
    }

    public function markCompleted(User $user): void
    {
        $user->forceFill([
            'onboarding_step' => null,
            'onboarding_completed_at' => now(),
        ])->save();
    }

    public function routeNameForStep(string $step): string
    {
        $this->assertValidStep($step);

        return match ($step) {
            self::STEP_ACCOUNT => 'onboarding.account.show',
            self::STEP_CATEGORIES => 'onboarding.categories.show',
            self::STEP_BUDGET => 'onboarding.budget.show',
            self::STEP_TRANSACTION => 'onboarding.transaction.show',
        };
    }

    public function starterCategories(): Collection
    {
        return collect(config('onboarding.starter_categories', []));
    }

    /**
     * @return array<int, string>
     */
    public function starterCategoryIds(): array
    {
        return $this->starterCategories()->pluck('id')->all();
    }

    protected function assertValidStep(string $step): void
    {
        if (! array_key_exists($step, $this->steps())) {
            throw new InvalidArgumentException(sprintf('Unknown onboarding step [%s].', $step));
        }
    }
}
