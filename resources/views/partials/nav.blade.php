<nav class="border-b border-[var(--border)] bg-[var(--bg-panel-strong)] px-6 py-4 text-[var(--text-primary)] shadow-[var(--shadow-sm)] backdrop-blur-2xl">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-xl font-bold text-grok">
            <x-application-logo class="h-9 w-9 shrink-0" />
            <span>TrackerYarn</span>
        </a>

        <div class="flex items-center gap-6 text-sm font-medium">
            <a href="{{ route('dashboard') }}"
               class="{{ request()->routeIs('dashboard') ? 'text-brand' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }} transition">
                Dashboard
            </a>
            <a href="{{ route('accounts.index') }}"
               class="{{ request()->routeIs('accounts.*') ? 'text-brand' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }} transition">
                Accounts
            </a>
            <a href="{{ route('categories.index') }}"
               class="{{ request()->routeIs('categories.*') ? 'text-brand' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }} transition">
                Categories
            </a>
            <a href="{{ route('transactions.index') }}"
               class="{{ request()->routeIs('transactions.*') ? 'text-brand' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }} transition">
                Transactions
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-danger">Logout</button>
            </form>
        </div>
    </div>
</nav>
