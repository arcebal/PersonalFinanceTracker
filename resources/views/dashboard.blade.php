@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

    <div class="page-shell">

        <section class="page-header">
            <div class="page-title-block">
                <h1 class="page-title">Financial dashboard</h1>
                <p class="page-subtitle">Monitor your total position, recent cash movement, and category-level spending in
                    one place.</p>
            </div>
            <div class="page-actions">
                <button id="exportPdfBtn" class="btn-primary">Export PDF report</button>
                <span id="exportStatus" class="text-sm text-muted"></span>
            </div>
        </section>

        <section class="metric-grid">
            <article class="metric-card">
                <div class="metric-icon">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 8.5h18v9A2.5 2.5 0 0118.5 20h-13A2.5 2.5 0 013 17.5v-9zm0 3h18M7 5h10" />
                    </svg>
                </div>
                <div class="metric-label">Total balance</div>
                <div class="metric-value text-grok">₱{{ number_format($totalBalance, 2) }}</div>
            </article>

            <article class="metric-card">
                <div class="metric-icon">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 17l4-4 3 3 5-6" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16" />
                    </svg>
                </div>
                <div class="metric-label">Total income</div>
                <div class="metric-value text-income">₱{{ number_format($totalIncome, 2) }}</div>
            </article>

            <article class="metric-card">
                <div class="metric-icon">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7l10 10M17 7 7 17" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16" />
                    </svg>
                </div>
                <div class="metric-label">Total expenses</div>
                <div class="metric-value text-expense">₱{{ number_format($totalExpense, 2) }}</div>
            </article>
        </section>

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">

            {{-- Budget health --}}
            <article class="section-card">
                <div class="panel-heading">
                    <h2 class="text-xl font-extrabold text-[var(--text-primary)]">Budget health</h2>
                </div>
                @if ($budgetSummaries->count())
                    <div class="stack-list mt-3" style="max-height:320px;overflow-y:auto;">
                        @foreach ($budgetSummaries as $summary)
                            @php
                                $pct =
                                    $summary['amount'] > 0
                                        ? min(round(($summary['spent'] / $summary['amount']) * 100), 100)
                                        : 0;
                                $isOver = $summary['spent'] > $summary['amount'];
                            @endphp
                            <article class="list-card" style="cursor:pointer;"
                                onclick="window.location='{{ route('budgets.index') }}'">
                                <div class="flex items-center justify-between mb-1.5">
                                    <div class="table-title text-sm">{{ $summary['category']->name }}</div>
                                    <span class="text-xs font-bold {{ $isOver ? 'text-expense' : 'text-muted' }}">
                                        ₱{{ number_format($summary['spent'], 2) }}
                                        <span class="font-normal text-muted"> /
                                            ₱{{ number_format($summary['amount'], 2) }}</span>
                                    </span>
                                </div>
                                <div class="w-full rounded-full h-1" style="background-color:var(--bg-panel-soft,#e5e7eb);">
                                    <div class="h-1 rounded-full transition-all"
                                        style="width:{{ $pct }}%;background-color:{{ $isOver ? 'var(--color-expense,#ef4444)' : 'var(--color-income,#22c55e)' }};">
                                    </div>
                                </div>
                                <div class="text-xs text-muted mt-1">
                                    @if ($isOver)
                                        Over by ₱{{ number_format($summary['spent'] - $summary['amount'], 2) }}
                                    @else
                                        ₱{{ number_format($summary['amount'] - $summary['spent'], 2) }} remaining &middot;
                                        {{ $pct }}% used
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16M7 16V9m5 7V5m5 11v-4" />
                            </svg>
                        </div>
                        <p>No budgets set yet. Create one to start tracking your spending limits.</p>
                    </div>
                @endif
            </article>

            {{-- Upcoming due items --}}
            <article class="section-card">
                <div class="panel-heading">
                    <h2 class="text-xl font-extrabold text-[var(--text-primary)]">Upcoming due items</h2>
                </div>
                @if ($upcomingRecurringTransactions->count())
                    <div class="stack-list mt-3" style="max-height:320px;overflow-y:auto;">
                        @foreach ($upcomingRecurringTransactions as $item)
                            <article class="list-card" style="cursor:pointer;"
                                onclick="window.location='{{ route('recurring-transactions.index') }}'">
                                <div class="table-title text-sm">{{ $item->description }}</div>
                                <div class="text-xs text-muted mt-0.5">
                                    {{ $item->next_due_date->format('M d, Y') }} &middot; {{ ucfirst($item->frequency) }}
                                </div>
                                <div class="inline-meta mt-1.5">
                                    <span class="{{ $item->type === 'income' ? 'badge-income' : 'badge-expense' }}">
                                        {{ $item->type === 'income' ? '+' : '-' }}₱{{ number_format($item->amount, 2) }}
                                    </span>
                                    <span class="topbar-pill">{{ $item->account->name }}</span>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 4v4m10-4v4M4 10h16M6 20h12a2 2 0 002-2V8H4v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p>No recurring items are due in the next seven days.</p>
                    </div>
                @endif
            </article>

            {{-- Money signals --}}
            <article class="section-card">
                <div class="panel-heading">
                    <h2 class="text-xl font-extrabold text-[var(--text-primary)]">Money signals</h2>
                </div>
                @php
                    $signals = [];
                    if ($totalExpense > $totalIncome && $totalIncome > 0) {
                        $signals[] = [
                            'title' => 'Spending exceeds income',
                            'note' => 'Your total expenses this period are higher than your recorded income.',
                            'route' => route('transactions.index'),
                        ];
                    }
                    foreach ($budgetSummaries as $summary) {
                        if ($summary['spent'] > $summary['amount'] && $summary['amount'] > 0) {
                            $signals[] = [
                                'title' => $summary['category']->name . ' is over budget',
                                'note' =>
                                    'Over by ₱' .
                                    number_format($summary['spent'] - $summary['amount'], 2) .
                                    ' this month.',
                                'route' => route('budgets.index'),
                            ];
                        }
                    }
                    $topExpense = collect($budgetSummaries)->sortByDesc('spent')->first();
                    if ($topExpense && $topExpense['spent'] > 0) {
                        $signals[] = [
                            'title' => $topExpense['category']->name . ' is your top expense category this month',
                            'note' => 'Current spend is ₱' . number_format($topExpense['spent'], 2) . '.',
                            'route' => route('transactions.index'),
                        ];
                    }
                    if ($upcomingRecurringTransactions->count()) {
                        $signals[] = [
                            'title' => $upcomingRecurringTransactions->count() . ' recurring item(s) due within 7 days',
                            'note' => 'Review your upcoming scheduled transactions.',
                            'route' => route('recurring-transactions.index'),
                        ];
                    }
                @endphp
                @if (count($signals))
                    <div class="stack-list mt-3" style="max-height:320px;overflow-y:auto;">
                        @foreach ($signals as $signal)
                            <article class="list-card" style="cursor:pointer;"
                                onclick="window.location='{{ $signal['route'] }}'">
                                <div class="table-title text-sm">{{ $signal['title'] }}</div>
                                <div class="text-xs text-muted mt-0.5">{{ $signal['note'] }}</div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <p>No signals right now. Your finances look on track.</p>
                    </div>
                @endif
            </article>

        </div>

        <div class="dash-panels">
            <article class="section-card">
                <div class="panel-heading mb-4">
                    <div class="panel-title-block">
                        <h2 class="text-xl font-extrabold text-[var(--text-primary)]">Expense breakdown</h2>
                    </div>
                </div>
                <div class="flex items-center justify-center" style="height:300px;">
                    <canvas id="pieChart" style="max-width:280px;max-height:280px;"></canvas>
                </div>
            </article>

            <article class="section-card">
                <div class="panel-heading mb-4">
                    <div class="panel-title-block">
                        <h2 class="text-xl font-extrabold text-[var(--text-primary)]">Income vs expense</h2>
                    </div>
                </div>
                <div style="height:300px;position:relative;">
                    <canvas id="barChart" style="max-height:280px;"></canvas>
                </div>
            </article>
        </div>

    </div>

@endsection

@section('scripts')
    <script>
        window.chartData = {
            pieLabels: @json($pieLabels ?? []),
            pieData: @json($pieData ?? []),
            pieColors: @json($pieColors ?? []),
            months: @json($months ?? []),
            income: @json($income ?? []),
            expense: @json($expense ?? [])
        };
        window.budgetData = {
            labels: @json($budgetLabels ?? []),
            budgets: @json($budgetAmounts ?? []),
            spent: @json($spentAmounts ?? [])
        };
    </script>
@endsection
