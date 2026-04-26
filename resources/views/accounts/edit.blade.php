@extends('layouts.app')
@section('title', 'Edit Account')
@section('content')

<div class="page-shell">
    <section class="page-header">
        <div class="page-title-block">
            <span class="page-kicker">Accounts</span>
            <h1 class="page-title">Edit account</h1>
            <p class="page-subtitle">Update the name, type, and available balance for this source.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('accounts.index') }}" class="btn-secondary">Back to accounts</a>
        </div>
    </section>

    <section class="form-layout">
        <div class="form-panel">
            <div class="panel-heading mb-6">
                <div class="panel-title-block">
                    <span class="page-kicker">Update source</span>
                    <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">{{ $account->name }}</h2>
                    <p class="panel-subtitle">Keep account information accurate so reporting and transaction entry remain reliable.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('accounts.update', $account) }}" class="auth-form">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-field">
                        <label class="field-label">Account name</label>
                        <input type="text" name="name" value="{{ old('name', $account->name) }}">
                        @error('name')<p class="text-sm text-expense">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-field md:col-span-6">
                        <label class="field-label">Type</label>
                        <select name="type">
                            <option value="cash" {{ $account->type === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="e-wallet" {{ $account->type === 'e-wallet' ? 'selected' : '' }}>E-Wallet (GCash, Maya)</option>
                        </select>
                    </div>

                    <div class="form-field md:col-span-6">
                        <label class="field-label">Balance (₱)</label>
                        <input type="number" name="balance" value="{{ old('balance', $account->balance) }}" step="0.01">
                        @error('balance')<p class="text-sm text-expense">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-field">
                        <label class="field-label">Description</label>
                        <input type="text" name="description" value="{{ old('description', $account->description) }}">
                    </div>
                </div>

                <div class="page-actions mt-6">
                    <button type="submit" class="btn-primary">Update account</button>
                    <a href="{{ route('accounts.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </section>
</div>

@endsection
