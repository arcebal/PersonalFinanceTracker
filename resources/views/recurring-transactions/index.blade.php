@extends('layouts.app')
@section('title', 'Recurring Items')
@section('content')

<div class="page-shell">
    <section class="page-header">
        <div class="page-title-block">
            <span class="page-kicker">Recurring</span>
            <h1 class="page-title">Recurring plans</h1>
            <p class="page-subtitle">Manage bills, subscriptions, salaries, and other repeating money events from one schedule list.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('notifications.index') }}" class="btn-secondary">View inbox</a>
            <a href="{{ route('recurring-transactions.create') }}" class="btn-primary">Add recurring item</a>
        </div>
    </section>

    <section class="metric-grid">
        <article class="metric-card">
            <div class="metric-label">Active plans</div>
            <div class="metric-value">{{ $stats['active'] }}</div>
            <div class="metric-note">Schedules currently participating in reminders and due tracking.</div>
        </article>
        <article class="metric-card">
            <div class="metric-label">Due now</div>
            <div class="metric-value text-expense">{{ $stats['due'] }}</div>
            <div class="metric-note">Items you can confirm into the transaction log immediately.</div>
        </article>
        <article class="metric-card">
            <div class="metric-label">Due soon</div>
            <div class="metric-value text-brand">{{ $stats['upcoming'] }}</div>
            <div class="metric-note">Schedules currently inside their reminder window.</div>
        </article>
    </section>

    <section class="table-shell">
        @if ($recurringTransactions->count())
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Schedule</th>
                            <th>Next due</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recurringTransactions as $item)
                            @php
                                $isDue = $item->isDue();
                                $isDueSoon = $item->isDueSoon();
                            @endphp
                            <tr>
                                <td>
                                    <div class="table-title">{{ $item->description }}</div>
                                    <div class="text-sm text-muted">
                                        {{ $item->account->name }} · {{ $item->category->name }} · {{ ucfirst($item->type) }}
                                    </div>
                                </td>
                                <td>
                                    Every {{ $item->interval }} {{ $item->frequency === 'weekly' ? \Illuminate\Support\Str::plural('week', $item->interval) : \Illuminate\Support\Str::plural('month', $item->interval) }}
                                </td>
                                <td>
                                    @if ($item->next_due_date)
                                        {{ $item->next_due_date->format('M d, Y') }}
                                    @else
                                        Completed
                                    @endif
                                </td>
                                <td class="{{ $item->type === 'income' ? 'text-income' : 'text-expense' }} font-bold">
                                    {{ $item->type === 'income' ? '+' : '-' }}P{{ number_format($item->amount, 2) }}
                                </td>
                                <td>
                                    @if (! $item->is_active)
                                        <span class="status-chip status-muted">Paused</span>
                                    @elseif ($isDue)
                                        <span class="status-chip status-danger">Due now</span>
                                    @elseif ($isDueSoon)
                                        <span class="status-chip status-warning">Due soon</span>
                                    @else
                                        <span class="status-chip status-safe">Scheduled</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="table-actions">
                                        @if ($item->is_active && $isDue)
                                            <form action="{{ route('recurring-transactions.confirm', $item) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-primary">Confirm</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('recurring-transactions.edit', $item) }}" class="btn-secondary">Edit</a>
                                        <form action="{{ route('recurring-transactions.destroy', $item) }}" method="POST" class="swal-delete-form">
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 4v4m10-4v4M4 10h16M6 20h12a2 2 0 002-2V8H4v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <p>No recurring items yet. Add your first salary, rent, utility, or subscription plan to start getting reminders.</p>
                <a href="{{ route('recurring-transactions.create') }}" class="btn-primary">Create recurring item</a>
            </div>
        @endif
    </section>
</div>

@endsection
