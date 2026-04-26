@extends('layouts.app')
@section('title','Budgets')
@section('content')

@php
    $selectedMonth = \Illuminate\Support\Carbon::createFromDate($year, $month, 1)->format('F Y');
@endphp

<div class="page-shell">
    <section class="page-header">
        <div class="page-title-block">
            <span class="page-kicker">Budgets</span>
            <h1 class="page-title">Monthly budget planning</h1>
            <p class="page-subtitle">Set target amounts by category for {{ $selectedMonth }} and compare them against your actual expense activity on the dashboard.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('dashboard') }}" class="btn-secondary">Back to dashboard</a>
        </div>
    </section>

    <section class="section-card">
        <div class="panel-heading mb-6">
            <div class="panel-title-block">
                <span class="page-kicker">Period</span>
                <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">Choose a budget month</h2>
                <p class="panel-subtitle">Switch between months or years before editing category targets.</p>
            </div>
        </div>

        <form method="GET" action="{{ route('budgets.index') }}" class="auth-form">
            <div class="form-grid">
                <div class="form-field md:col-span-4">
                    <label class="field-label">Year</label>
                    <input type="number" name="year" value="{{ $year }}">
                </div>

                <div class="form-field md:col-span-4">
                    <label class="field-label">Month</label>
                    <input type="number" name="month" min="1" max="12" value="{{ $month }}">
                </div>

                <div class="form-field md:col-span-4">
                    <label class="field-label">Load period</label>
                    <button class="btn-secondary">Go</button>
                </div>
            </div>
        </form>
    </section>

    <section class="form-panel">
        <div class="panel-heading mb-6">
            <div class="panel-title-block">
                <span class="page-kicker">Targets</span>
                <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">Budget amounts for {{ $selectedMonth }}</h2>
                <p class="panel-subtitle">Set zero or leave blank for categories that do not need an active monthly target.</p>
            </div>
        </div>

        @if ($categories->count())
            <form method="POST" action="{{ route('budgets.store') }}" class="auth-form">
                @csrf
                <input type="hidden" name="year" value="{{ $year }}">
                <input type="hidden" name="month" value="{{ $month }}">

                <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
                    @foreach($categories as $cat)
                        <div class="section-card">
                            <div class="page-header">
                                <div class="page-title-block">
                                    <h3 class="text-lg font-extrabold text-[var(--text-primary)]">{{ $cat->name }}</h3>
                                    <p class="page-subtitle">{{ ucfirst($cat->type) }} category</p>
                                </div>
                                <span class="{{ $cat->type === 'income' ? 'badge-income' : 'badge-expense' }}">
                                    {{ ucfirst($cat->type) }}
                                </span>
                            </div>

                            <div class="form-field mt-5">
                                <label class="field-label">Budget amount (₱)</label>
                                <input
                                    name="amounts[{{ $cat->id }}]"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    value="{{ optional($budgets->get($cat->id))->amount ?? '' }}"
                                    placeholder="0.00"
                                >
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="page-actions mt-6">
                    <button type="submit" class="btn-primary">Save budgets</button>
                </div>
            </form>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h4l2 2h4v8H7V7zm-2 0h2v10a2 2 0 002 2h8" />
                    </svg>
                </div>
                <p>Create categories before setting budget targets.</p>
                <a href="{{ route('categories.create') }}" class="btn-primary">Add category</a>
            </div>
        @endif
    </section>
</div>

@endsection
