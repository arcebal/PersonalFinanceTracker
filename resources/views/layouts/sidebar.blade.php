@php
    $user = Auth::user();
    $avatarUrl = $user->avatarUrl();
    $sidebarUnreadNotificationCount = $user->appNotifications()->whereNull('read_at')->count();
@endphp

<aside class="sidebar-shell flex h-full flex-col gap-6">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="brand-lockup">
            <span class="brand-mark">
                <x-application-logo class="h-7 w-7" />
            </span>
            <span class="brand-copy">
                <strong>TrackerYarn</strong>
            </span>
        </a>

    </div>

    <nav class="sidebar-nav flex-1 flex flex-col justify-start pt-1">

        <a href="{{ route('dashboard') }}"
            class="sidebar-nav-link flex items-center {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
            <span class="sidebar-nav-icon">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 12l7-7 4 4 5-5v16H4V12z" />
                </svg>
            </span>
            <span>Dashboard</span>
            <x-sidebar-badge :count="$sidebarBadges['dashboard']['count']" :type="$sidebarBadges['dashboard']['type']" :hidden="$sidebarBadges['dashboard']['hidden']" key="dashboard" />
        </a>

        <a href="{{ route('accounts.index') }}"
            class="sidebar-nav-link flex items-center {{ request()->routeIs('accounts.*') ? 'is-active' : '' }}">
            <span class="sidebar-nav-icon">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 8.5h18v9A2.5 2.5 0 0118.5 20h-13A2.5 2.5 0 013 17.5v-9zm0 3h18M7 5h10" />
                </svg>
            </span>
            <span>Accounts</span>
            <x-sidebar-badge :count="$sidebarBadges['accounts']['count']" :type="$sidebarBadges['accounts']['type']" :hidden="$sidebarBadges['accounts']['hidden']" key="accounts" />
        </a>

        <a href="{{ route('transactions.index') }}"
            class="sidebar-nav-link flex items-center {{ request()->routeIs('transactions.*') ? 'is-active' : '' }}">
            <span class="sidebar-nav-icon">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 7h14M5 12h14M9 17h10" />
                </svg>
            </span>
            <span>Transactions</span>
            <x-sidebar-badge :count="$sidebarBadges['transactions']['count']" :type="$sidebarBadges['transactions']['type']" :hidden="$sidebarBadges['transactions']['hidden']" key="transactions" />
        </a>

        <a href="{{ route('categories.index') }}"
            class="sidebar-nav-link flex items-center {{ request()->routeIs('categories.*') ? 'is-active' : '' }}">
            <span class="sidebar-nav-icon">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7 7h4l2 2h4v8H7V7zm-2 0h2v10a2 2 0 002 2h8" />
                </svg>
            </span>
            <span>Categories</span>
            <x-sidebar-badge :count="$sidebarBadges['categories']['count']" :type="$sidebarBadges['categories']['type']" :hidden="$sidebarBadges['categories']['hidden']" key="categories" />
        </a>

        <a href="{{ route('budgets.index') }}"
            class="sidebar-nav-link flex items-center {{ request()->routeIs('budgets.*') ? 'is-active' : '' }}">
            <span class="sidebar-nav-icon">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16M7 16V9m5 7V5m5 11v-4" />
                </svg>
            </span>
            <span>Budgets</span>
            <x-sidebar-badge :count="$sidebarBadges['budgets']['count']" :type="$sidebarBadges['budgets']['type']" :hidden="$sidebarBadges['budgets']['hidden']" key="budgets" />
        </a>

        <a href="{{ route('recurring-transactions.index') }}"
            class="sidebar-nav-link flex items-center {{ request()->routeIs('recurring-transactions.*') ? 'is-active' : '' }}">
            <span class="sidebar-nav-icon">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7 4v4m10-4v4M4 10h16M6 20h12a2 2 0 002-2V8H4v10a2 2 0 002 2z" />
                </svg>
            </span>
            <span>Recurring</span>
            <x-sidebar-badge :count="$sidebarBadges['recurring']['count']" :type="$sidebarBadges['recurring']['type']" :hidden="$sidebarBadges['recurring']['hidden']" key="recurring" />
        </a>

        <a href="{{ route('profile.edit') }}"
            class="sidebar-nav-link flex items-center {{ request()->routeIs('profile.*') ? 'is-active' : '' }}">
            <span class="sidebar-nav-icon">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 12a4 4 0 100-8 4 4 0 000 8zm-7 8a7 7 0 0114 0" />
                </svg>
            </span>
            <span>Profile</span>
            <x-sidebar-badge :count="$sidebarBadges['profile']['count']" :type="$sidebarBadges['profile']['type']" :hidden="$sidebarBadges['profile']['hidden']" key="profile" />
        </a>
    </nav>

    <div class="relative mt-auto" x-data="{ open: false }" @click.away="open = false">
        <button type="button"
            class="sidebar-user w-full rounded-[20px] border border-[var(--border)] bg-[var(--bg-panel-soft)] px-3 py-3 text-left"
            @click="open = ! open">
            <span class="flex items-center gap-3">
                @if ($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="sidebar-avatar-image">
                @else
                    <span class="sidebar-avatar" aria-hidden="true">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 12a4 4 0 100-8 4 4 0 000 8zm-7 8a7 7 0 0114 0" />
                        </svg>
                    </span>
                @endif
                <span class="min-w-0">
                    <span
                        class="block truncate text-sm font-bold text-[var(--text-primary)]">{{ $user->name }}</span>
                    <span class="block truncate text-xs text-[var(--text-tertiary)]">{{ $user->email }}</span>
                </span>
            </span>
            <svg class="h-4 w-4 shrink-0 text-[var(--text-tertiary)]" viewBox="0 0 20 20" fill="currentColor"
                aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M5.23 7.21a.75.75 0 011.06.02L10 11.29l3.71-4.06a.75.75 0 111.1 1.02l-4.24 4.66a.75.75 0 01-1.1 0L5.21 8.27a.75.75 0 01.02-1.06z"
                    clip-rule="evenodd" />
            </svg>
        </button>

        <div x-cloak x-show="open" x-transition
            class="absolute bottom-full left-0 mb-3 w-full rounded-[20px] border border-[var(--border)] bg-[var(--bg-panel-strong)] p-2 shadow-2xl backdrop-blur-2xl">
            <a href="{{ route('settings.profile') }}" class="sidebar-nav-link">Settings</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-nav-link w-full text-left text-expense">Log out</button>
            </form>
        </div>
    </div>

    @if (auth()->check())
        @once
            <script>
                (function() {
                    const fetchBadges = () => {
                        fetch('{{ route('api.sidebar-badges') }}', {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                                }
                            })
                            .then(r => r.json())
                            .then(data => {
                                window.dispatchEvent(new CustomEvent('badge-update', {
                                    detail: data
                                }));
                            })
                            .catch(() => {});
                    };

                    setInterval(fetchBadges, 30000);

                    document.addEventListener('visibilitychange', () => {
                        if (!document.hidden) fetchBadges();
                    });
                })
                ();
            </script>
        @endonce
    @endif
</aside>
