@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<div class="page-shell">
    <section class="page-header">
        <div class="page-title-block">
            <span class="page-kicker">Overview</span>
            <h1 class="page-title">Financial dashboard</h1>
            <p class="page-subtitle">Monitor your total position, recent cash movement, and category-level spending in one place.</p>
        </div>

        <div class="page-actions">
            <a href="{{ route('budgets.index') }}" class="btn-secondary">Manage budgets</a>
            <a href="{{ route('recurring-transactions.index') }}" class="btn-secondary">Recurring items</a>
            <button id="exportPdfBtn" class="btn-primary">Export PDF report</button>
        </div>
    </section>

    <div class="flex items-center justify-end">
        <div id="exportStatus" class="text-sm text-muted"></div>
    </div>

    <section class="content-grid">
        <article class="section-card col-span-12 xl:col-span-5">
            <div class="panel-heading">
                <div class="panel-title-block">
                    <span class="page-kicker">Insights</span>
                    <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">Money signals</h2>
                    <p class="panel-subtitle">Rule-based insights call out trends, risks, and upcoming recurring activity.</p>
                </div>
            </div>

            @if ($insights->count())
                <div class="stack-list mt-6">
                    @foreach ($insights as $insight)
                        <article class="insight-card">
                            <div class="table-title">{{ $insight['headline'] }}</div>
                            <p class="text-sm text-muted">{{ $insight['body'] }}</p>
                            @if (! empty($insight['action_url']))
                                <a href="{{ $insight['action_url'] }}" class="helper-link">Open related view</a>
                            @endif
                        </article>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16M7 16V9m5 7V5m5 11v-4" />
                        </svg>
                    </div>
                    <p>Add more transactions and budgets to unlock insight cards here.</p>
                </div>
            @endif
        </article>

        <article class="section-card col-span-12 xl:col-span-3">
            <div class="panel-heading">
                <div class="panel-title-block">
                    <span class="page-kicker">Recurring</span>
                    <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">Upcoming due items</h2>
                    <p class="panel-subtitle">Bills and repeating income scheduled in the next seven days.</p>
                </div>
                <a href="{{ route('recurring-transactions.index') }}" class="btn-secondary">Manage</a>
            </div>

            @if ($upcomingRecurringTransactions->count())
                <div class="stack-list mt-6">
                    @foreach ($upcomingRecurringTransactions as $item)
                        <article class="list-card">
                            <div class="table-title">{{ $item->description }}</div>
                            <div class="text-sm text-muted">{{ $item->next_due_date->format('M d, Y') }} · {{ ucfirst($item->frequency) }}</div>
                            <div class="inline-meta">
                                <span class="{{ $item->type === 'income' ? 'badge-income' : 'badge-expense' }}">
                                    {{ $item->type === 'income' ? '+' : '-' }}P{{ number_format($item->amount, 2) }}
                                </span>
                                <span class="topbar-pill">{{ $item->account->name }}</span>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 4v4m10-4v4M4 10h16M6 20h12a2 2 0 002-2V8H4v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p>No recurring items are due in the next seven days.</p>
                </div>
            @endif
        </article>

        <article class="section-card col-span-12 xl:col-span-4">
            <div class="panel-heading">
                <div class="panel-title-block">
                    <span class="page-kicker">Inbox</span>
                    <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">Notification preview</h2>
                    <p class="panel-subtitle">{{ $unreadNotificationCount }} unread notification{{ $unreadNotificationCount === 1 ? '' : 's' }} right now.</p>
                </div>
                <a href="{{ route('notifications.index') }}" class="btn-secondary">Open inbox</a>
            </div>

            @if ($notificationPreview->count())
                <div class="stack-list mt-6">
                    @foreach ($notificationPreview as $notification)
                        <article class="list-card">
                            <div class="inline-meta">
                                <span class="status-chip {{ $notification->read_at ? 'status-muted' : 'status-warning' }}">
                                    {{ $notification->read_at ? 'Read' : 'Unread' }}
                                </span>
                                <span class="text-sm text-muted">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="table-title">{{ $notification->title }}</div>
                            <p class="text-sm text-muted">{{ $notification->message }}</p>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0h6z" />
                        </svg>
                    </div>
                    <p>No notifications yet. Scheduled reminders and budget alerts will appear here.</p>
                </div>
            @endif
        </article>
    </section>

    <section class="metric-grid">
        <article class="metric-card">
            <div class="metric-icon">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.5h18v9A2.5 2.5 0 0118.5 20h-13A2.5 2.5 0 013 17.5v-9zm0 3h18M7 5h10" />
                </svg>
            </div>
            <div class="metric-label">Total balance</div>
            <div class="metric-value text-grok">₱{{ number_format($totalBalance, 2) }}</div>
            <div class="metric-note">Your combined account balance across all tracked sources.</div>
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
            <div class="metric-note">All recorded incoming cash flow to date.</div>
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
            <div class="metric-note">All tracked outgoing cash flow across categories.</div>
        </article>
    </section>

    <section class="chart-grid">
        <article class="section-card col-span-12 xl:col-span-5">
            <div class="panel-heading">
                <div class="panel-title-block">
                    <span class="page-kicker">Spend mix</span>
                    <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">Expense breakdown</h2>
                    <p class="panel-subtitle">See which categories are driving your current spending.</p>
                </div>
            </div>
            <div class="mt-6">
                @if (count($pieData))
                    <canvas id="pieChart" height="300"></canvas>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 3a1 1 0 011 1v8h8a1 1 0 011 1 10 10 0 11-10-10z" />
                            </svg>
                        </div>
                        <p>Add expense transactions to generate a category breakdown.</p>
                    </div>
                @endif
            </div>
        </article>

        <article class="section-card col-span-12 xl:col-span-7">
            <div class="panel-heading">
                <div class="panel-title-block">
                    <span class="page-kicker">Cash flow</span>
                    <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">Income vs expense</h2>
                    <p class="panel-subtitle">Compare recent monthly incoming cash against outgoing spend.</p>
                </div>
            </div>
            <div class="mt-6">
                <canvas id="barChart" height="300"></canvas>
            </div>
        </article>

        <article class="section-card col-span-12">
            <div class="panel-heading">
                <div class="panel-title-block">
                    <span class="page-kicker">Budget pulse</span>
                    <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">Budget vs actual</h2>
                    <p class="panel-subtitle">Compare this month’s planned amounts with recorded category spending.</p>
                </div>
                <a href="{{ route('budgets.index') }}" class="btn-secondary">Edit budgets</a>
            </div>

            <div class="mt-6">
                @if (count($budgetLabels ?? []))
                    <canvas id="budgetChart" height="140"></canvas>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16M7 16V9m5 7V5m5 11v-4" />
                            </svg>
                        </div>
                        <p>Create monthly expense budgets to compare targets and actual spend on this dashboard.</p>
                    </div>
                @endif
            </div>
        </article>
    </section>

    <section class="table-shell">
        <div class="panel-heading mb-4">
            <div class="panel-title-block">
                <span class="page-kicker">Activity</span>
                <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">Recent transactions</h2>
                <p class="panel-subtitle">Your latest income and expense activity across tracked accounts.</p>
            </div>
            <a href="{{ route('transactions.index') }}" class="btn-secondary">View all transactions</a>
        </div>

        @if ($recentTrans->count())
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Account</th>
                            <th>Type</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTrans as $t)
                            <tr>
                                <td>{{ \Illuminate\Support\Carbon::parse($t->transaction_date)->format('M d, Y') }}</td>
                                <td>
                                    <div class="table-title">{{ $t->description ?: 'Recorded transaction' }}</div>
                                    <div class="text-sm text-muted">Tracked in {{ $t->account->name }}</div>
                                </td>
                                <td>
                                    <span class="table-badge" style="border-color: {{ $t->category->color }}33; background-color: {{ $t->category->color }}1f; color: {{ $t->category->color }};">
                                        {{ $t->category->name }}
                                    </span>
                                </td>
                                <td>{{ $t->account->name }}</td>
                                <td>
                                    <span class="{{ $t->type === 'income' ? 'badge-income' : 'badge-expense' }}">
                                        {{ ucfirst($t->type) }}
                                    </span>
                                </td>
                                <td class="{{ $t->type === 'income' ? 'text-income' : 'text-expense' }} font-bold">
                                    {{ $t->type === 'income' ? '+' : '-' }}₱{{ number_format($t->amount, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 7h14M5 12h14M9 17h10" />
                    </svg>
                </div>
                <p>No transactions yet. Start by adding your first income or expense entry.</p>
                <a href="{{ route('transactions.create') }}" class="btn-primary">Add transaction</a>
            </div>
        @endif
    </section>
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
