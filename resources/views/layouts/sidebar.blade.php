<aside class="w-64 bg-white/70 backdrop-blur-sm border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700 min-h-screen flex flex-col">
    <div class="p-4">
        @if(Route::has('dashboard'))
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
            <!-- Question mark circle icon -->
            <svg class="h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 14a1 1 0 110 2 1 1 0 010-2zm1.07-7.75c.2.23.33.52.33.83 0 .7-.56 1.07-1.42 1.6-.8.49-1.24.86-1.24 1.82v.5a1 1 0 11-2 0v-.5c0-1.32.7-2.06 1.6-2.6.83-.51 1.46-.9 1.46-1.82 0-.6-.34-1.2-.94-1.56A2 2 0 1010 6a1 1 0 112 0c0 .26.19.51.43.75z" clip-rule="evenodd"/></svg>
            <span class="font-semibold text-lg">TrackerYarn</span>
        </a>
        @else
        <div class="flex items-center space-x-2">
            <svg class="h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 14a1 1 0 110 2 1 1 0 010-2zm1.07-7.75c.2.23.33.52.33.83 0 .7-.56 1.07-1.42 1.6-.8.49-1.24.86-1.24 1.82v.5a1 1 0 11-2 0v-.5c0-1.32.7-2.06 1.6-2.6.83-.51 1.46-.9 1.46-1.82 0-.6-.34-1.2-.94-1.56A2 2 0 1010 6a1 1 0 112 0c0 .26.19.51.43.75z" clip-rule="evenodd"/></svg>
            <span class="font-semibold text-lg">TrackerYarn</span>
        </div>
        @endif
    </div>

    <nav class="px-2 py-4">
        @if(Route::has('dashboard'))
        <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
            Dashboard
        </a>
        @endif

        @if(Route::has('accounts.index'))
        <a href="{{ route('accounts.index') }}" class="mt-1 block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('accounts.*') ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
            Accounts
        </a>
        @endif

        @if(Route::has('transactions.index'))
        <a href="{{ route('transactions.index') }}" class="mt-1 block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('transactions.*') ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
            Transactions
        </a>
        @endif

        @if(Route::has('categories.index'))
        <a href="{{ route('categories.index') }}" class="mt-1 block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('categories.*') ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
            Categories
        </a>
        @endif
    </nav>

    <div class="mt-auto p-4 border-t border-gray-100">
        <div class="flex items-center justify-between">
            <div class="relative" x-data="{open:false}">
                <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                    <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" alt="avatar" class="h-8 w-8 rounded-full">
                    <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                    <svg class="h-4 w-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.293l3.71-4.06a.75.75 0 111.1 1.02l-4.25 4.656a.75.75 0 01-1.1 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                </button>

                <div x-show="open" x-transition class="absolute left-0 -mb-2 bottom-12 w-48 bg-white border rounded-md shadow-lg py-1 z-20">
                    @if(Route::has('profile.edit'))
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit profile</a>
                    @else
                        <a href="#" class="block px-4 py-2 text-sm text-gray-400">Edit profile</a>
                    @endif

                    @if(Route::has('settings.profile'))
                        <a href="{{ route('settings.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                    @else
                        <a href="#" class="block px-4 py-2 text-sm text-gray-400">Settings</a>
                    @endif
                </div>
            </div>

            <div>
                @if(Route::has('logout'))
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-3 py-2 rounded-md text-sm font-medium text-red-600 hover:bg-red-50">Log out</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</aside>