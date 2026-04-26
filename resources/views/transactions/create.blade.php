@extends('layouts.app')
@section('title', 'Add Transaction')
@section('content')

<div class="page-shell">
    <section class="page-header">
        <div class="page-title-block">
            <span class="page-kicker">Transactions</span>
            <h1 class="page-title">Add transaction</h1>
            <p class="page-subtitle">Log income or expense activity and assign it to the right account and category.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('transactions.index') }}" class="btn-secondary">Back to transactions</a>
        </div>
    </section>

    <section class="form-layout">
        <div class="form-panel">
            <form method="POST" action="{{ route('transactions.store') }}" class="auth-form">
                @csrf

                <div class="panel-heading mb-6">
                    <div class="panel-title-block">
                        <span class="page-kicker">New entry</span>
                        <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">Transaction details</h2>
                        <p class="panel-subtitle">The category list will automatically filter based on whether you choose income or expense.</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-field md:col-span-6">
                        <label class="field-label">Type</label>
                        <select name="type" id="tx-type">
                            <option value="income">Income</option>
                            <option value="expense">Expense</option>
                        </select>
                    </div>

                    <div class="form-field md:col-span-6">
                        <label class="field-label">Account</label>
                        <select name="account_id" id="tx-account">
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}" data-balance="{{ $acc->balance }}" {{ request('account_id') == $acc->id ? 'selected' : '' }}>
                                    {{ $acc->name }} (₱{{ number_format($acc->balance, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-field md:col-span-6">
                        <label class="field-label">Category</label>
                        <select name="category_id" id="tx-category">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" data-type="{{ $cat->type }}">{{ $cat->name }} ({{ ucfirst($cat->type) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-field md:col-span-6">
                        <label class="field-label">Amount (₱)</label>
                        <input type="number" name="amount" step="0.01" min="0.01">
                        @error('amount')<p class="text-sm text-expense">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-field md:col-span-6">
                        <label class="field-label">Date</label>
                        <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="form-field md:col-span-6">
                        <label class="field-label">Description</label>
                        <input type="text" name="description" placeholder="Optional note">
                    </div>
                </div>

                <div class="page-actions mt-6">
                    <button type="submit" class="btn-primary">Save transaction</button>
                    <a href="{{ route('transactions.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </section>
</div>

@endsection
