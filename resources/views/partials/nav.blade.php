<nav class="bg-white dark:bg-[#071126] text-white px-6 py-4 shadow-md">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-xl font-bold text-grok">
            <x-application-logo class="h-9 w-9 shrink-0" />
            <span>TrackerYarn</span>
        </a>

        <div class="flex items-center gap-6 text-sm font-medium">
            <a href="{{ route('dashboard') }}"
               class="{{ request()->routeIs('dashboard') ? 'text-grok' : 'text-muted hover:text-white' }} transition">
                Dashboard
            </a>
            <a href="{{ route('accounts.index') }}"
               class="{{ request()->routeIs('accounts.*') ? 'text-emerald-400' : 'text-gray-300 hover:text-white' }} transition">
                Accounts
            </a>
            <a href="{{ route('categories.index') }}"
               class="{{ request()->routeIs('categories.*') ? 'text-emerald-400' : 'text-gray-300 hover:text-white' }} transition">
                Categories
            </a>
            <a href="{{ route('transactions.index') }}"
               class="{{ request()->routeIs('transactions.*') ? 'text-emerald-400' : 'text-gray-300 hover:text-white' }} transition">
                Transactions
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-danger">Logout</button>
            </form>
        </div>
    </div>
</nav>
