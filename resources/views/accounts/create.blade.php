@extends('layouts.app')
@section('title', 'Add Account')
@section('content')

<div class="page-shell">
    <section class="page-header">
        <div class="page-title-block">
            <span class="page-kicker">Accounts</span>
            <h1 class="page-title">Add account</h1>
            <p class="page-subtitle">Create a balance source that can be used for future income and expense entries.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('accounts.index') }}" class="btn-secondary">Back to accounts</a>
        </div>
    </section>

    <section class="form-layout">
        <div class="form-panel">
            <div class="panel-heading mb-6">
                <div class="panel-title-block">
                    <span class="page-kicker">New source</span>
                    <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">Account details</h2>
                    <p class="panel-subtitle">Use clear names so this account is easy to identify during transaction entry.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('accounts.store') }}" class="auth-form">
                @csrf

                <div class="form-grid">
                    <div class="form-field">
                        <label class="field-label">Account name</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Main wallet">
                        @error('name')<p class="text-sm text-expense">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-field md:col-span-6">
                        <label class="field-label">Type</label>
                        <select name="type">
                            <option value="cash">Cash</option>
                            <option value="e-wallet">E-Wallet (GCash, Maya)</option>
                        </select>
                    </div>

                    <div class="form-field md:col-span-6">
                        <label class="field-label">Initial balance (₱)</label>
                        <input type="number" name="balance" value="{{ old('balance', 0) }}" step="0.01" min="0">
                        @error('balance')<p class="text-sm text-expense">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-field">
                        <label class="field-label">Description</label>
                        <input type="text" name="description" value="{{ old('description') }}" placeholder="Optional note about this account">
                        <p class="field-note">Examples: emergency cash, payroll account, daily spending wallet.</p>
                    </div>
                </div>

                <div class="page-actions mt-6">
                    <button type="submit" class="btn-primary">Save account</button>
                    <a href="{{ route('accounts.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </section>
</div>

@endsection
