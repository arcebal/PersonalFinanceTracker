@extends('layouts.app')
@section('title', 'Budgets')
@section('content')

    @php
        $selectedMonth = \Illuminate\Support\Carbon::createFromDate($year, $month, 1)->format('F Y');
    @endphp

    <div class="page-shell">
        <section class="page-header">
            <div class="page-title-block">
                <h1 class="page-title">Monthly budget planning</h1>
                <p class="page-subtitle">Set monthly budget targets by category and track them against your actual spending
                    on the dashboard.</p>
            </div>
        </section>

        <section class="form-panel">
            <div class="panel-heading mb-6">
                <div class="panel-title-block">
                    <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">Budget amounts for {{ $selectedMonth }}
                    </h2>
                </div>
                <form method="GET" action="{{ route('budgets.index') }}" id="periodForm" class="flex items-center gap-3">
                    <select name="month" onchange="document.getElementById('periodForm').submit()" class="field-label"
                        style="border:2px solid var(--border-subtle);border-radius:999px;padding:10px 20px;font-size:1rem;font-weight:700;background:var(--bg-panel,#fff);cursor:pointer;appearance:auto;min-width:120px;box-shadow:0 2px 8px rgba(0,0,0,0.07);">
                        <option value="" disabled {{ !request()->has('month') ? 'selected' : '' }}>Month</option>
                        @foreach (['January' => 1, 'February' => 2, 'March' => 3, 'April' => 4, 'May' => 5, 'June' => 6, 'July' => 7, 'August' => 8, 'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12] as $label => $num)
                            <option value="{{ $num }}"
                                {{ request()->has('month') && $num == $month ? 'selected' : '' }}>{{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <select name="year" onchange="document.getElementById('periodForm').submit()" class="field-label"
                        style="border:2px solid var(--border-subtle);border-radius:999px;padding:10px 20px;font-size:1rem;font-weight:700;background:var(--bg-panel,#fff);cursor:pointer;appearance:auto;min-width:100px;box-shadow:0 2px 8px rgba(0,0,0,0.07);">
                        <option value="" disabled {{ !request()->has('year') ? 'selected' : '' }}>Year</option>
                        @php
                            $availableYears = collect(range(now()->year - 1, now()->year + 2))
                                ->merge(
                                    \App\Models\Budget::where('user_id', auth()->id())
                                        ->select('year')
                                        ->distinct()
                                        ->pluck('year'),
                                )
                                ->unique()
                                ->sort()
                                ->values();
                        @endphp
                        @foreach ($availableYears as $y)
                            <option value="{{ $y }}"
                                {{ request()->has('year') && $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            @if ($categories->count())
                <form method="POST" action="{{ route('budgets.store') }}" class="auth-form">
                    @csrf
                    <input type="hidden" name="year" value="{{ $year }}">
                    <input type="hidden" name="month" value="{{ $month }}">

                    <div class="grid grid-cols-1 gap-4 xl:grid-cols-2 mt-6">
                        @foreach ($categories as $cat)
                            <div class="section-card">
                                <div class="page-header">
                                    <div class="page-title-block">
                                        <h3 class="text-lg font-extrabold text-[var(--text-primary)]">{{ $cat->name }}
                                        </h3>
                                    </div>
                                    <span class="{{ $cat->type === 'income' ? 'badge-income' : 'badge-expense' }}">
                                        {{ ucfirst($cat->type) }}
                                    </span>
                                </div>

                                <div class="form-field mt-5">
                                    <label class="field-label">Budget amount (₱)</label>
                                    <input name="amounts[{{ $cat->id }}]" type="number" step="0.01" min="0"
                                        value="{{ optional($budgets->get($cat->id))->amount ?? '' }}" placeholder="0.00">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="btn-primary">Save budgets</button>
                    </div>
                </form>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7 7h4l2 2h4v8H7V7zm-2 0h2v10a2 2 0 002 2h8" />
                        </svg>
                    </div>
                    <p>Create categories before setting budget targets.</p>
                    <a href="{{ route('categories.create') }}" class="btn-primary">Add category</a>
                </div>
            @endif
        </section>
    </div>

@endsection
