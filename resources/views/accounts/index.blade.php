@extends('layouts.app')
@section('title', 'Accounts')
@section('content')

@php
    $accountTotal = $accounts->sum('balance');
@endphp

<div class="page-shell">
    <section class="page-header">
        <div class="page-title-block">
            <span class="page-kicker">Accounts</span>
            <h1 class="page-title">Money sources</h1>
            <p class="page-subtitle">Keep cash, wallet, and e-wallet balances organized so transactions can be assigned cleanly.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('accounts.trashed') }}" class="btn-secondary">View trash</a>
            <a href="{{ route('accounts.create') }}" class="btn-primary">Add account</a>
        </div>
    </section>

    <section class="metric-grid">
        <article class="metric-card">
            <div class="metric-label">Tracked accounts</div>
            <div class="metric-value">{{ $accounts->count() }}</div>
            <div class="metric-note">Each account can be used when recording new transactions.</div>
        </article>
        <article class="metric-card">
            <div class="metric-label">Combined balance</div>
            <div class="metric-value text-grok">₱{{ number_format($accountTotal, 2) }}</div>
            <div class="metric-note">A quick view of total available money across accounts.</div>
        </article>
        <article class="metric-card">
            <div class="metric-label">Quick action</div>
            <div class="metric-value">Log faster</div>
            <div class="metric-note">Tap any account card below to open a prefilled transaction form.</div>
        </article>
    </section>

    <script>window._categories = @json($categories ?? []);</script>

    @if ($accounts->count())
        <section class="grid grid-cols-1 gap-4 xl:grid-cols-3">
            @foreach($accounts as $acc)
                <article class="section-card account-card cursor-pointer" data-account-id="{{ $acc->id }}" data-account-name="{{ $acc->name }}" data-account-balance="{{ $acc->balance }}">
                    <div class="panel-heading">
                        <div class="panel-title-block">
                            <span class="page-kicker">Account</span>
                            <h2 class="text-xl font-extrabold text-[var(--text-primary)]">{{ $acc->name }}</h2>
                        </div>
                        <span class="table-badge">{{ ucfirst($acc->type) }}</span>
                    </div>

                    <div class="mt-6">
                        <div class="metric-value text-grok">₱{{ number_format($acc->balance, 2) }}</div>
                        <p class="mt-3 text-sm text-muted">{{ $acc->description ?: 'No description added yet.' }}</p>
                    </div>

                    <div class="page-actions mt-6">
                        <a href="{{ route('accounts.edit', $acc) }}" onclick="event.stopPropagation()" class="btn-secondary flex-1">Edit</a>
                        <form action="{{ route('accounts.destroy', $acc) }}" method="POST" class="swal-delete-form flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="delete-btn btn-danger w-full" onclick="event.stopPropagation()">Delete</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </section>
    @else
        <section class="section-card">
            <div class="empty-state">
                <div class="empty-icon">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.5h18v9A2.5 2.5 0 0118.5 20h-13A2.5 2.5 0 013 17.5v-9zm0 3h18M7 5h10" />
                    </svg>
                </div>
                <p>No accounts yet. Add your first wallet, cash, or e-wallet account to begin tracking money.</p>
                <a href="{{ route('accounts.create') }}" class="btn-primary">Create account</a>
            </div>
        </section>
    @endif
</div>

@endsection
