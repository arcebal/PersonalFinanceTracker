<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientBalanceException;
use App\Models\Budget;
use App\Models\Category;
use App\Services\OnboardingService;
use App\Services\TransactionRecorder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    public function __construct(
        protected OnboardingService $onboardingService,
        protected TransactionRecorder $transactionRecorder,
    ) {
    }

    public function start(Request $request): RedirectResponse
    {
        return $this->redirectToCurrentStep($request->user());
    }

    public function showAccount(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->guardStep($request, OnboardingService::STEP_ACCOUNT)) {
            return $redirect;
        }

        return view('onboarding.account', $this->viewData(OnboardingService::STEP_ACCOUNT));
    }

    public function storeAccount(Request $request): RedirectResponse
    {
        if ($redirect = $this->guardStep($request, OnboardingService::STEP_ACCOUNT)) {
            return $redirect;
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['cash', 'e-wallet'])],
            'balance' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($request, $validated) {
            $request->user()->accounts()->create($validated);
            $this->onboardingService->advanceTo($request->user(), OnboardingService::STEP_CATEGORIES);
        });

        return redirect()->route('onboarding.categories.show');
    }

    public function showCategories(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->guardStep($request, OnboardingService::STEP_CATEGORIES)) {
            return $redirect;
        }

        $starterCategories = $this->onboardingService
            ->starterCategories()
            ->groupBy('type')
            ->map(fn ($group) => $group->values())
            ->all();

        return view('onboarding.categories', $this->viewData(OnboardingService::STEP_CATEGORIES, [
            'starterCategories' => $starterCategories,
            'defaultCategoryIds' => $this->onboardingService->starterCategoryIds(),
        ]));
    }

    public function storeCategories(Request $request): RedirectResponse
    {
        if ($redirect = $this->guardStep($request, OnboardingService::STEP_CATEGORIES)) {
            return $redirect;
        }

        $validated = $request->validate([
            'category_ids' => ['required', 'array'],
            'category_ids.*' => ['required', 'string', Rule::in($this->onboardingService->starterCategoryIds())],
        ]);

        $selectedCategories = $this->onboardingService
            ->starterCategories()
            ->whereIn('id', $validated['category_ids'])
            ->values();

        if ($selectedCategories->where('type', 'expense')->isEmpty()) {
            throw ValidationException::withMessages([
                'category_ids' => 'Choose at least one expense category to continue.',
            ]);
        }

        DB::transaction(function () use ($request, $selectedCategories) {
            foreach ($selectedCategories as $category) {
                Category::query()->updateOrCreate(
                    [
                        'user_id' => $request->user()->id,
                        'name' => $category['name'],
                        'type' => $category['type'],
                    ],
                    [
                        'color' => $category['color'],
                    ],
                );
            }

            $this->onboardingService->advanceTo($request->user(), OnboardingService::STEP_BUDGET);
        });

        return redirect()->route('onboarding.budget.show');
    }

    public function showBudget(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->guardStep($request, OnboardingService::STEP_BUDGET)) {
            return $redirect;
        }

        $budgetCategories = $request->user()->categories()
            ->where('type', 'expense')
            ->orderBy('name')
            ->get();

        return view('onboarding.budget', $this->viewData(OnboardingService::STEP_BUDGET, [
            'budgetCategories' => $budgetCategories,
            'selectedMonth' => now()->format('F Y'),
        ]));
    }

    public function storeBudget(Request $request): RedirectResponse
    {
        if ($redirect = $this->guardStep($request, OnboardingService::STEP_BUDGET)) {
            return $redirect;
        }

        $validated = $request->validate([
            'amounts' => ['nullable', 'array'],
            'amounts.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        $user = $request->user();
        $now = now();
        $categoryIds = $user->categories()
            ->where('type', 'expense')
            ->pluck('id');

        DB::transaction(function () use ($validated, $user, $now, $categoryIds) {
            foreach ($categoryIds as $categoryId) {
                $amount = (float) data_get($validated, "amounts.{$categoryId}", 0);

                if ($amount > 0) {
                    Budget::query()->updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'category_id' => $categoryId,
                            'year' => $now->year,
                            'month' => $now->month,
                        ],
                        [
                            'amount' => $amount,
                        ],
                    );

                    continue;
                }

                Budget::query()
                    ->where('user_id', $user->id)
                    ->where('category_id', $categoryId)
                    ->where('year', $now->year)
                    ->where('month', $now->month)
                    ->delete();
            }

            $this->onboardingService->advanceTo($user, OnboardingService::STEP_TRANSACTION);
        });

        return redirect()->route('onboarding.transaction.show');
    }

    public function skipBudget(Request $request): RedirectResponse
    {
        if ($redirect = $this->guardStep($request, OnboardingService::STEP_BUDGET)) {
            return $redirect;
        }

        $this->onboardingService->advanceTo($request->user(), OnboardingService::STEP_TRANSACTION);

        return redirect()->route('onboarding.transaction.show');
    }

    public function showTransaction(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->guardStep($request, OnboardingService::STEP_TRANSACTION)) {
            return $redirect;
        }

        $accounts = $request->user()->accounts()->orderBy('name')->get();
        $categories = $request->user()->categories()->orderBy('type')->orderBy('name')->get();
        $availableTransactionTypes = $categories->pluck('type')->unique()->values();

        return view('onboarding.transaction', $this->viewData(OnboardingService::STEP_TRANSACTION, [
            'accounts' => $accounts,
            'categories' => $categories,
            'availableTransactionTypes' => $availableTransactionTypes,
        ]));
    }

    public function storeTransaction(Request $request): RedirectResponse
    {
        if ($redirect = $this->guardStep($request, OnboardingService::STEP_TRANSACTION)) {
            return $redirect;
        }

        $validated = $request->validate([
            'account_id' => ['required', 'integer'],
            'category_id' => ['required', 'integer'],
            'type' => ['required', Rule::in(['income', 'expense'])],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['nullable', 'string', 'max:255'],
            'transaction_date' => ['required', 'date'],
        ]);

        try {
            $this->transactionRecorder->record($request->user(), $validated);
        } catch (InsufficientBalanceException $exception) {
            return back()->withErrors([
                'amount' => $exception->getMessage(),
            ])->withInput();
        }

        $this->onboardingService->markCompleted($request->user());

        return redirect()->route('dashboard')->with('success', 'Setup complete. Your workspace is ready.');
    }

    public function skipTransaction(Request $request): RedirectResponse
    {
        if ($redirect = $this->guardStep($request, OnboardingService::STEP_TRANSACTION)) {
            return $redirect;
        }

        $this->onboardingService->markCompleted($request->user());

        return redirect()->route('dashboard')->with('success', 'Setup complete. You can add transactions any time.');
    }

    protected function guardStep(Request $request, string $expectedStep): ?RedirectResponse
    {
        $user = $request->user();

        if ($user->hasCompletedOnboarding()) {
            return redirect()->route('dashboard');
        }

        $currentStep = $this->onboardingService->ensureStarted($user);

        if ($currentStep !== $expectedStep) {
            return redirect()->route($this->onboardingService->routeNameForStep($currentStep));
        }

        return null;
    }

    protected function redirectToCurrentStep($user): RedirectResponse
    {
        if ($user->hasCompletedOnboarding()) {
            return redirect()->route('dashboard');
        }

        $step = $this->onboardingService->ensureStarted($user);

        return redirect()->route($this->onboardingService->routeNameForStep($step));
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return array<string, mixed>
     */
    protected function viewData(string $step, array $extra = []): array
    {
        $steps = $this->onboardingService->steps();
        $stepKeys = array_keys($steps);
        $currentStepNumber = array_search($step, $stepKeys, true) + 1;

        return array_merge([
            'steps' => $steps,
            'currentStep' => $step,
            'currentStepNumber' => $currentStepNumber,
            'currentStepMeta' => $steps[$step],
            'totalSteps' => count($steps),
        ], $extra);
    }
}
