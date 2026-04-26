@extends('layouts.app')
@section('title', 'Transactions')
@section('content')

<div class="page-shell">
    <section class="page-header">
        <div class="page-title-block">
            <span class="page-kicker">Transactions</span>
            <h1 class="page-title">Cash flow log</h1>
            <p class="page-subtitle">Review income and expense activity, then narrow the table with account, category, and date filters.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('transactions.export') }}" class="btn-secondary">Export CSV</a>
            <a href="{{ route('transactions.trashed') }}" class="btn-secondary">View trash</a>
            <a href="{{ route('transactions.create') }}" class="btn-primary">Add transaction</a>
        </div>
    </section>

    <section class="section-card">
        <div class="panel-heading mb-6">
            <div class="panel-title-block">
                <span class="page-kicker">Filters</span>
                <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">Refine the transaction list</h2>
                <p class="panel-subtitle">Search by text, date, account, category, or type.</p>
            </div>
        </div>

        <form method="GET" action="{{ route('transactions.index') }}" class="auth-form">
            <div class="form-grid">
                <div class="form-field md:col-span-3">
                    <label class="field-label">Start date</label>
                    <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}">
                </div>

                <div class="form-field md:col-span-3">
                    <label class="field-label">End date</label>
                    <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}">
                </div>

                <div class="form-field md:col-span-3">
                    <label class="field-label">Category</label>
                    <select name="category_id">
                        <option value="">All</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ isset($filters['category_id']) && $filters['category_id'] == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field md:col-span-3">
                    <label class="field-label">Account</label>
                    <select name="account_id">
                        <option value="">All</option>
                        @foreach($accounts as $a)
                            <option value="{{ $a->id }}" {{ isset($filters['account_id']) && $filters['account_id'] == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field md:col-span-3">
                    <label class="field-label">Type</label>
                    <select name="type">
                        <option value="">All</option>
                        <option value="income" {{ isset($filters['type']) && $filters['type'] == 'income' ? 'selected' : '' }}>Income</option>
                        <option value="expense" {{ isset($filters['type']) && $filters['type'] == 'expense' ? 'selected' : '' }}>Expense</option>
                    </select>
                </div>

                <div class="form-field md:col-span-6">
                    <label class="field-label">Search</label>
                    <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Description or amount">
                </div>

                <div class="form-field md:col-span-3">
                    <label class="field-label">Apply filters</label>
                    <button class="btn-primary">Filter transactions</button>
                </div>
            </div>
        </form>
    </section>

    <section class="table-shell">
        @if ($transactions->count())
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $t)
                            <tr>
                                <td>{{ \Illuminate\Support\Carbon::parse($t->transaction_date)->format('M d, Y') }}</td>
                                <td>
                                    <div class="table-title">{{ $t->description ?: 'Recorded transaction' }}</div>
                                    <div class="text-sm text-muted">Saved to {{ $t->account->name }}</div>
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
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('transactions.edit', $t) }}" class="btn-secondary">Edit</a>
                                        <form action="{{ route('transactions.destroy', $t) }}" method="POST" class="swal-delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="delete-btn btn-danger">Delete</button>
                                        </form>
                                    </div>
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
                <p>No transactions matched the current filters.</p>
                <a href="{{ route('transactions.create') }}" class="btn-primary">Add transaction</a>
            </div>
        @endif
    </section>
</div>

@endsection
