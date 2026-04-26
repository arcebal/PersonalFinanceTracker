@php
    $user = Auth::user();
    $avatarUrl = $user->avatarUrl();
@endphp

<aside class="sidebar-shell flex h-full flex-col gap-6">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="brand-lockup">
            <span class="brand-mark">
                <x-application-logo class="h-7 w-7" />
            </span>
            <span class="brand-copy">
                <strong>TrackerYarn</strong>
                <span>Personal finance command center</span>
            </span>
        </a>

    </div>

    <div class="sidebar-card">
        <div class="sidebar-group">Welcome</div>
        <div class="sidebar-welcome mt-3">
            <div class="sidebar-welcome-row">
                @if ($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="sidebar-welcome-avatar">
                @else
                    <span class="sidebar-welcome-avatar sidebar-welcome-avatar-fallback" aria-hidden="true">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a4 4 0 100-8 4 4 0 000 8zm-7 8a7 7 0 0114 0" />
                        </svg>
                    </span>
                @endif

                <div class="min-w-0">
                    <div class="sidebar-welcome-title">Welcome back, {{ $user->name }}</div>
                </div>
            </div>

            <p class="sidebar-welcome-copy text-sm text-muted">Track balances, monitor cash flow, and keep budgeting in one calm workspace.</p>
        </div>
    </div>

    <nav class="sidebar-nav">
        <span class="sidebar-group">Workspace</span>

        <a href="{{ route('dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
            <span class="sidebar-nav-icon">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 12l7-7 4 4 5-5v16H4V12z" />
                </svg>
            </span>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('accounts.index') }}" class="sidebar-nav-link {{ request()->routeIs('accounts.*') ? 'is-active' : '' }}">
            <span class="sidebar-nav-icon">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.5h18v9A2.5 2.5 0 0118.5 20h-13A2.5 2.5 0 013 17.5v-9zm0 3h18M7 5h10" />
                </svg>
            </span>
            <span>Accounts</span>
        </a>

        <a href="{{ route('transactions.index') }}" class="sidebar-nav-link {{ request()->routeIs('transactions.*') ? 'is-active' : '' }}">
            <span class="sidebar-nav-icon">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 7h14M5 12h14M9 17h10" />
                </svg>
            </span>
            <span>Transactions</span>
        </a>

        <a href="{{ route('categories.index') }}" class="sidebar-nav-link {{ request()->routeIs('categories.*') ? 'is-active' : '' }}">
            <span class="sidebar-nav-icon">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h4l2 2h4v8H7V7zm-2 0h2v10a2 2 0 002 2h8" />
                </svg>
            </span>
            <span>Categories</span>
        </a>

        <a href="{{ route('budgets.index') }}" class="sidebar-nav-link {{ request()->routeIs('budgets.*') ? 'is-active' : '' }}">
            <span class="sidebar-nav-icon">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16M7 16V9m5 7V5m5 11v-4" />
                </svg>
            </span>
            <span>Budgets</span>
        </a>

        <a href="{{ route('profile.edit') }}" class="sidebar-nav-link {{ request()->routeIs('profile.*') ? 'is-active' : '' }}">
            <span class="sidebar-nav-icon">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a4 4 0 100-8 4 4 0 000 8zm-7 8a7 7 0 0114 0" />
                </svg>
            </span>
            <span>Profile</span>
        </a>
    </nav>

    <div class="sidebar-card mt-auto space-y-3">
        <div class="sidebar-group">Quick actions</div>
        <div class="grid gap-3">
            <a href="{{ route('transactions.create') }}" class="btn-primary">New transaction</a>
            <a href="{{ route('accounts.create') }}" class="btn-secondary">Add account</a>
        </div>
    </div>

    <div class="relative" x-data="{ open: false }" @click.away="open = false">
        <button type="button" class="sidebar-user w-full rounded-[20px] border border-[var(--border)] bg-[var(--bg-panel-soft)] px-3 py-3 text-left" @click="open = ! open">
            <span class="flex items-center gap-3">
                @if ($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="sidebar-avatar-image">
                @else
                    <span class="sidebar-avatar" aria-hidden="true">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a4 4 0 100-8 4 4 0 000 8zm-7 8a7 7 0 0114 0" />
                        </svg>
                    </span>
                @endif
                <span class="min-w-0">
                    <span class="block truncate text-sm font-bold text-[var(--text-primary)]">{{ $user->name }}</span>
                    <span class="block truncate text-xs text-[var(--text-tertiary)]">{{ $user->email }}</span>
                </span>
            </span>
            <svg class="h-4 w-4 shrink-0 text-[var(--text-tertiary)]" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.29l3.71-4.06a.75.75 0 111.1 1.02l-4.24 4.66a.75.75 0 01-1.1 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
        </button>

        <div
            x-cloak
            x-show="open"
            x-transition
            class="absolute bottom-full left-0 mb-3 w-full rounded-[20px] border border-[var(--border)] bg-[var(--bg-panel-strong)] p-2 shadow-2xl backdrop-blur-2xl"
        >
            <a href="{{ route('profile.edit') }}" class="sidebar-nav-link">Edit profile</a>
            <a href="{{ route('settings.profile') }}" class="sidebar-nav-link">Settings</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-nav-link w-full text-left text-expense">Log out</button>
            </form>
        </div>
    </div>
</aside>
