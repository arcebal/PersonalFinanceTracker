@extends('layouts.app')
@section('title','Deleted Accounts')
@section('content')

<div class="page-shell">
    <section class="page-header">
        <div class="page-title-block">
            <span class="page-kicker">Accounts</span>
            <h1 class="page-title">Deleted accounts</h1>
            <p class="page-subtitle">Restore archived accounts or permanently remove them from the system.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('accounts.index') }}" class="btn-secondary">Back to accounts</a>
        </div>
    </section>

    <section class="section-card">
        @forelse($accounts as $a)
            <div class="trash-card mb-4 last:mb-0">
                <div class="page-header">
                    <div class="page-title-block">
                        <h2 class="text-xl font-extrabold text-[var(--text-primary)]">{{ $a->name }}</h2>
                        <p class="page-subtitle">{{ $a->description ?: 'No description available.' }}</p>
                    </div>
                    <div class="page-actions">
                        <form action="{{ route('accounts.restore', $a->id) }}" method="POST">
                            @csrf
                            <button class="btn-secondary">Restore</button>
                        </form>
                        <form action="{{ route('accounts.forceDelete', $a->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Permanently delete?')" class="btn-danger">Delete permanently</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12m-9 4v6m6-6v6M8 7l1-2h6l1 2m-9 0v11a2 2 0 002 2h6a2 2 0 002-2V7" />
                    </svg>
                </div>
                <p>No deleted accounts.</p>
            </div>
        @endforelse
    </section>
</div>

@endsection
