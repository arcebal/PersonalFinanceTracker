@extends('layouts.app')
@section('title', 'Recurring Items')
@section('content')

    <div class="page-shell">
        <section class="page-header">
            <div class="page-title-block">
                <h1 class="page-title">Recurring plans</h1>
                <p class="page-subtitle">Manage bills, subscriptions, salaries, and other repeating money events from one
                    schedule list.</p>
            </div>
            <div class="page-actions">
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
                                    </td>
                                    <td>
                                        Every {{ $item->interval }}
                                        {{ $item->frequency === 'weekly' ? \Illuminate\Support\Str::plural('week', $item->interval) : \Illuminate\Support\Str::plural('month', $item->interval) }}
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
                                        @if (!$item->is_active)
                                            <span class="status-chip status-muted">Paused</span>
                                        @elseif ($isDue)
                                            <span class="status-chip status-danger">Due now</span>
                                        @elseif ($isDueSoon)
                                            <span class="status-chip status-warning">Due soon</span>
                                        @else
                                            <span class="status-chip status-safe">Scheduled</span>
                                        @endif
                                    </td>
                                    <td style="white-space:nowrap;width:1%;">
                                        <div style="display:flex;align-items:center;gap:8px;width:fit-content;">
                                            <a href="{{ route('recurring-transactions.edit', $item) }}"
                                                class="btn-secondary">Edit</a>

                                            <form action="{{ route('recurring-transactions.destroy', $item) }}"
                                                method="POST" class="swal-delete-form" style="margin:0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="delete-btn" title="Delete"
                                                    style="background:none;border:none;padding:4px;cursor:pointer;color:#ef4444;">
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M10 11v6M14 11v6" />
                                                    </svg>
                                                </button>
                                            </form>

                                            @if ($item->is_active && $isDue)
                                                <span
                                                    style="color:#cbd5e1;font-weight:300;font-size:1.1rem;user-select:none;">|</span>
                                                <form action="{{ route('recurring-transactions.confirm', $item) }}"
                                                    method="POST" style="margin:0;">
                                                    @csrf
                                                    <button type="submit"
                                                        style="background:transparent;color:#16a34a;border:1.5px solid #16a34a;border-radius:6px;padding:5px 12px;font-size:0.875rem;font-weight:600;cursor:pointer;line-height:1.25;"
                                                        onmouseover="this.style.background='#f0fdf4'"
                                                        onmouseout="this.style.background='transparent'">Confirm</button>
                                                </form>
                                            @endif
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
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7 4v4m10-4v4M4 10h16M6 20h12a2 2 0 002-2V8H4v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p>No recurring items yet. Add your first salary, rent, utility, or subscription plan to start getting
                        reminders.</p>
                </div>
            @endif
        </section>
    </div>

@endsection
