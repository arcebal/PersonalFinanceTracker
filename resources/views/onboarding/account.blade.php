@extends('layouts.onboarding')

@section('title', 'Create First Account')

@section('content')
<form method="POST" action="{{ route('onboarding.account.store') }}" class="auth-form">
    @csrf

    <div class="form-grid">
        <div class="form-field">
            <label class="field-label" for="account-name">Account name</label>
            <input id="account-name" type="text" name="name" value="{{ old('name') }}" placeholder="Main wallet">
            <p class="field-note">Use the account you reach for most often so your first reports make sense immediately.</p>
        </div>

        <div class="form-field md:col-span-6">
            <label class="field-label" for="account-type">Type</label>
            <select id="account-type" name="type">
                <option value="cash" @selected(old('type') === 'cash')>Cash</option>
                <option value="e-wallet" @selected(old('type') === 'e-wallet')>E-Wallet</option>
            </select>
        </div>

        <div class="form-field md:col-span-6">
            <label class="field-label" for="account-balance">Current balance (₱)</label>
            <input id="account-balance" type="number" name="balance" step="0.01" min="0" value="{{ old('balance', 0) }}">
        </div>

        <div class="form-field">
            <label class="field-label" for="account-description">Description</label>
            <input id="account-description" type="text" name="description" value="{{ old('description') }}" placeholder="Optional note about this account">
        </div>
    </div>

    <div class="page-actions mt-6">
        <button type="submit" class="btn-primary">Save and continue</button>
    </div>
</form>
@endsection
