@extends('layouts.app')
@section('title', 'Notifications')
@section('content')

<div class="page-shell">
    <section class="page-header">
        <div class="page-title-block">
            <span class="page-kicker">Inbox</span>
            <h1 class="page-title">Notifications</h1>
            <p class="page-subtitle">Review due reminders, budget alerts, inactivity nudges, and month-end summaries in one place.</p>
        </div>
        <div class="page-actions">
            @if ($unreadCount > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-secondary">Mark all as read</button>
                </form>
            @endif
            <a href="{{ route('dashboard') }}" class="btn-primary">Back to dashboard</a>
        </div>
    </section>

    <section class="section-card">
        <div class="panel-heading">
            <div class="panel-title-block">
                <span class="page-kicker">Status</span>
                <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">Unread summary</h2>
                <p class="panel-subtitle">{{ $unreadCount }} unread notification{{ $unreadCount === 1 ? '' : 's' }} currently need attention.</p>
            </div>
        </div>
    </section>

    @if ($notifications->count())
        <section class="stack-list">
            @foreach ($notifications as $notification)
                <article class="section-card notification-card {{ $notification->read_at ? 'is-read' : 'is-unread' }}">
                    <div class="page-header">
                        <div class="page-title-block">
                            <div class="inline-meta">
                                <span class="status-chip {{ $notification->read_at ? 'status-muted' : 'status-warning' }}">
                                    {{ $notification->read_at ? 'Read' : 'Unread' }}
                                </span>
                                <span class="topbar-pill">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            <h2 class="text-xl font-extrabold text-[var(--text-primary)]">{{ $notification->title }}</h2>
                            <p class="panel-subtitle">{{ $notification->message }}</p>
                        </div>

                        <div class="page-actions">
                            @if ($notification->action_url)
                                <a href="{{ $notification->action_url }}" class="btn-secondary">Open</a>
                            @endif

                            @if (! $notification->read_at)
                                <form action="{{ route('notifications.read', $notification) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-primary">Mark as read</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </section>
    @else
        <section class="section-card">
            <div class="empty-state">
                <div class="empty-icon">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0h6z" />
                    </svg>
                </div>
                <p>No notifications yet. Reminder, budget, and summary events will appear here once activity starts triggering them.</p>
            </div>
        </section>
    @endif
</div>

@endsection
