@extends('layouts.onboarding')

@section('title', 'Choose Starter Categories')

@section('content')
@php
    $selectedIds = old('category_ids', $defaultCategoryIds);
@endphp

<form method="POST" action="{{ route('onboarding.categories.store') }}" class="auth-form">
    @csrf

    <div class="stack-list">
        <section class="section-card">
            <div class="panel-heading">
                <div class="panel-title-block">
                    <span class="page-kicker">Income</span>
                    <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">Starting income categories</h2>
                    <p class="panel-subtitle">These are optional, but picking at least one helps when you want to log income during onboarding.</p>
                </div>
            </div>

            <div class="settings-choice-grid mt-6">
                @foreach (($starterCategories['income'] ?? collect()) as $category)
                    <label class="settings-choice-card">
                        <input
                            class="settings-choice-input"
                            type="checkbox"
                            name="category_ids[]"
                            value="{{ $category['id'] }}"
                            @checked(in_array($category['id'], $selectedIds, true))
                        >
                        <span class="settings-choice-copy">
                            <span class="settings-choice-title">{{ $category['name'] }}</span>
                            <span class="settings-choice-note">{{ $category['color'] }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
        </section>

        <section class="section-card">
            <div class="panel-heading">
                <div class="panel-title-block">
                    <span class="page-kicker">Expense</span>
                    <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">Starting expense categories</h2>
                    <p class="panel-subtitle">Choose at least one expense category. Budgets and spending insights depend on these.</p>
                </div>
            </div>

            <div class="settings-choice-grid mt-6">
                @foreach (($starterCategories['expense'] ?? collect()) as $category)
                    <label class="settings-choice-card">
                        <input
                            class="settings-choice-input"
                            type="checkbox"
                            name="category_ids[]"
                            value="{{ $category['id'] }}"
                            @checked(in_array($category['id'], $selectedIds, true))
                        >
                        <span class="settings-choice-copy">
                            <span class="settings-choice-title">{{ $category['name'] }}</span>
                            <span class="settings-choice-note">{{ $category['color'] }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
        </section>
    </div>

    <div class="page-actions mt-6">
        <button type="submit" class="btn-primary">Create categories and continue</button>
    </div>
</form>
@endsection
