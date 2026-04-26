@extends('layouts.onboarding')

@section('title', 'Set First Budget')

@section('content')
<form method="POST" action="{{ route('onboarding.budget.store') }}" class="auth-form">
    @csrf

    <section class="section-card">
        <div class="panel-heading">
            <div class="panel-title-block">
                <span class="page-kicker">Budget month</span>
                <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">{{ $selectedMonth }}</h2>
                <p class="panel-subtitle">Leave any amount blank or set it to zero if you do not want a budget target for that category yet.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-2 mt-6">
            @foreach ($budgetCategories as $category)
                <div class="section-card">
                    <div class="page-header">
                        <div class="page-title-block">
                            <h3 class="text-lg font-extrabold text-[var(--text-primary)]">{{ $category->name }}</h3>
                            <p class="page-subtitle">Expense category</p>
                        </div>
                        <span class="badge-expense">Expense</span>
                    </div>

                    <div class="form-field mt-5">
                        <label class="field-label" for="budget-{{ $category->id }}">Budget amount (₱)</label>
                        <input
                            id="budget-{{ $category->id }}"
                            type="number"
                            name="amounts[{{ $category->id }}]"
                            step="0.01"
                            min="0"
                            value="{{ old("amounts.{$category->id}") }}"
                            placeholder="0.00"
                        >
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <div class="page-actions mt-6">
        <button type="submit" class="btn-primary">Save budgets and continue</button>
        <button type="submit" formaction="{{ route('onboarding.budget.skip') }}" class="btn-secondary">Skip for now</button>
    </div>
</form>
@endsection
