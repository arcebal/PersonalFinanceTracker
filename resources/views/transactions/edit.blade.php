@extends('layouts.app')
@section('title', 'Edit Transaction')
@section('content')

<div class="page-shell">
    <section class="page-header">
        <div class="page-title-block">
            <span class="page-kicker">Transactions</span>
            <h1 class="page-title">Edit transaction</h1>
            <p class="page-subtitle">Update the amount, type, date, account, or category for this entry.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('transactions.index') }}" class="btn-secondary">Back to transactions</a>
        </div>
    </section>

    <section class="form-layout">
        <div class="form-panel">
            <form method="POST" action="{{ route('transactions.update', $transaction) }}" class="auth-form">
                @csrf
                @method('PUT')

                <div class="panel-heading mb-6">
                    <div class="panel-title-block">
                        <span class="page-kicker">Update entry</span>
                        <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">{{ $transaction->description ?: 'Transaction' }}</h2>
                        <p class="panel-subtitle">Changing the type will automatically narrow the category options to match.</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-field md:col-span-6">
                        <label class="field-label">Type</label>
                        <select name="type" id="tx-type">
                            <option value="income" {{ $transaction->type === 'income' ? 'selected' : '' }}>Income</option>
                            <option value="expense" {{ $transaction->type === 'expense' ? 'selected' : '' }}>Expense</option>
                        </select>
                    </div>

                    <div class="form-field md:col-span-6">
                        <label class="field-label">Account</label>
                        <select name="account_id" id="tx-account">
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}" data-balance="{{ $acc->balance }}" {{ $transaction->account_id == $acc->id ? 'selected' : '' }}>
                                    {{ $acc->name }} (₱{{ number_format($acc->balance, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-field md:col-span-6">
                        <label class="field-label">Category</label>
                        <select name="category_id" id="tx-category">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" data-type="{{ $cat->type }}" {{ $transaction->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }} ({{ ucfirst($cat->type) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-field md:col-span-6">
                        <label class="field-label">Amount (₱)</label>
                        <input type="number" name="amount" step="0.01" value="{{ $transaction->amount }}">
                    </div>

                    <div class="form-field md:col-span-6">
                        <label class="field-label">Date</label>
                        <input type="date" name="transaction_date" value="{{ $transaction->transaction_date }}">
                    </div>

                    <div class="form-field md:col-span-6">
                        <label class="field-label">Description</label>
                        <input type="text" name="description" value="{{ $transaction->description }}">
                    </div>
                </div>

                <div class="page-actions mt-6">
                    <button type="submit" class="btn-primary">Update transaction</button>
                    <a href="{{ route('transactions.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </section>
</div>

@endsection
