@extends('layouts.onboarding')

@section('title', 'Add First Transaction')

@section('content')
@php
    $defaultType = old('type', $availableTransactionTypes->first());
@endphp

<form method="POST" action="{{ route('onboarding.transaction.store') }}" class="auth-form">
    @csrf

    <div class="form-grid">
        <div class="form-field md:col-span-6">
            <label class="field-label" for="tx-type">Type</label>
            <select id="tx-type" name="type">
                @foreach ($availableTransactionTypes as $type)
                    <option value="{{ $type }}" @selected($defaultType === $type)>{{ ucfirst($type) }}</option>
                @endforeach
            </select>
            @if ($availableTransactionTypes->count() === 1)
                <p class="field-note">Only {{ $availableTransactionTypes->first() }} categories are available right now based on your starter selection.</p>
            @endif
        </div>

        <div class="form-field md:col-span-6">
            <label class="field-label" for="tx-account">Account</label>
            <select id="tx-account" name="account_id">
                @foreach ($accounts as $account)
                    <option value="{{ $account->id }}" @selected((string) old('account_id') === (string) $account->id)>
                        {{ $account->name }} (₱{{ number_format($account->balance, 2) }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-field md:col-span-6">
            <label class="field-label" for="tx-category">Category</label>
            <select id="tx-category" name="category_id">
                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        data-type="{{ $category->type }}"
                        @selected((string) old('category_id') === (string) $category->id)
                    >
                        {{ $category->name }} ({{ ucfirst($category->type) }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-field md:col-span-6">
            <label class="field-label" for="tx-amount">Amount (₱)</label>
            <input id="tx-amount" type="number" name="amount" step="0.01" min="0.01" value="{{ old('amount') }}">
        </div>

        <div class="form-field md:col-span-6">
            <label class="field-label" for="tx-date">Date</label>
            <input id="tx-date" type="date" name="transaction_date" value="{{ old('transaction_date', now()->toDateString()) }}">
        </div>

        <div class="form-field md:col-span-6">
            <label class="field-label" for="tx-description">Description</label>
            <input id="tx-description" type="text" name="description" value="{{ old('description') }}" placeholder="Optional note">
        </div>
    </div>

    <div class="page-actions mt-6">
        <button type="submit" class="btn-primary">Save transaction and finish</button>
        <button type="submit" formaction="{{ route('onboarding.transaction.skip') }}" class="btn-secondary">Finish without a transaction</button>
    </div>
</form>
@endsection
