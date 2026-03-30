<nav class="bg-gray-900 text-white px-6 py-4 shadow-md">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-emerald-400">💰 FinanceTracker</a>

        <div class="flex items-center gap-6 text-sm font-medium">
            <a href="{{ route('dashboard') }}"
               class="{{ request()->routeIs('dashboard') ? 'text-emerald-400' : 'text-gray-300 hover:text-white' }} transition">
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
                <button type="submit"
                    class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-1.5 rounded-lg transition">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>