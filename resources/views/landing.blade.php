<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'PersonalFinance') }} - Welcome</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @keyframes float { 0%,100%{transform:translateY(0)}50%{transform:translateY(-16px)} }
        @keyframes fadeInUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
        @keyframes slideInRight { from{opacity:0;transform:translateX(40px)} to{opacity:1;transform:translateX(0)} }
        .animate-float{animation:float 3s ease-in-out infinite}
        .animate-fade-in-up{animation:fadeInUp .7s ease-out forwards}
        .animate-slide-in-right{animation:slideInRight .7s ease-out forwards}
        .gradient-bg{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%)}
        .gradient-text{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
    </style>
</head>
<body class="bg-gray-50">

<nav class="fixed w-full bg-white/90 backdrop-blur-md shadow-sm z-50" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center">
                    <!-- question-mark icon -->
                    <svg class="h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 14a1 1 0 110 2 1 1 0 010-2zm1.07-7.75c.2.23.33.52.33.83 0 .7-.56 1.07-1.42 1.6-.8.49-1.24.86-1.24 1.82v.5a1 1 0 11-2 0v-.5c0-1.32.7-2.06 1.6-2.6.83-.51 1.46-.9 1.46-1.82 0-.6-.34-1.2-.94-1.56A2 2 0 1010 6a1 1 0 112 0c0 .26.19.51.43.75z" clip-rule="evenodd"/></svg>
                    <span class="ml-2 text-xl font-bold gradient-text">TrackerYarn</span>
                </div>
            </div>

            <div class="hidden md:flex items-center space-x-6">
                <a href="#features" class="text-gray-700 hover:text-indigo-600">Features</a>
                @if(Route::has('login'))
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600">Login</a>
                @endif
                @if(Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Get Started</a>
                @endif
            </div>

            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-white border-t">
        <div class="px-4 py-3 space-y-3">
            <a href="#features" class="block text-gray-700">Features</a>
            @if(Route::has('login'))<a href="{{ route('login') }}" class="block text-gray-700">Login</a>@endif
            @if(Route::has('register'))<a href="{{ route('register') }}" class="block inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Get Started</a>@endif
        </div>
    </div>
</nav>

<!-- Hero: copied top/animation/heading -->
<section class="pt-24 pb-20 px-4 sm:px-6 lg:px-8 gradient-bg overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="text-white space-y-8">
                <div class="animate-fade-in-up" style="animation-delay: 0.1s; opacity: 0;">
                    <span class="inline-block bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium mb-4">
                        ⚡ Powered by Tyron Daton
                    </span>
                    <h1 class="text-5xl sm:text-6xl font-bold leading-tight">
                        Take Control of Your
                        <span class="block mt-2">Finances Today</span>
                    </h1>
                </div>

                <p class="text-xl text-purple-100 animate-fade-in-up" style="animation-delay: 0.3s; opacity: 0;">
                    Track expenses, manage budgets, and achieve your financial goals with a beautiful and powerful expense tracker.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 animate-fade-in-up" style="animation-delay: 0.5s; opacity: 0;">
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 text-center">
                            Start Free Trial
                        </a>
                    @endif
                </div>

                <div class="flex items-center gap-8 pt-4 animate-fade-in-up" style="animation-delay: 0.7s; opacity: 0;">
                    <div>
                        <div class="text-3xl font-bold">10K+</div>
                        <div class="text-purple-200 text-sm">Active Users</div>
                    </div>
                    <div class="h-12 w-px bg-purple-300"></div>
                    <div>
                        <div class="text-3xl font-bold">$2M+</div>
                        <div class="text-purple-200 text-sm">Tracked</div>
                    </div>
                    <div class="h-12 w-px bg-purple-300"></div>
                    <div>
                        <div class="text-3xl font-bold">4.9★</div>
                        <div class="text-purple-200 text-sm">Rating</div>
                    </div>
                </div>
            </div>

            <div class="relative animate-slide-in-right" style="animation-delay: 0.4s; opacity: 0;">
                <div class="absolute inset-0 bg-white/10 backdrop-blur-sm rounded-2xl transform rotate-3"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl p-6 animate-float">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-800">This Month</h3>
                            <span class="text-sm text-gray-500">{{ now()->format('F Y') }}</span>
                        </div>

                        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl p-6 text-white">
                            <div class="text-sm opacity-90 mb-2">Total Spent</div>
                            <div class="text-4xl font-bold">$3,247.85</div>
                            <div class="text-sm mt-2 flex items-center">
                                <span class="text-green-300">↓ 12% from last month</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-red-50 rounded-lg p-4">
                                <div class="text-xs text-gray-600 mb-1">Food</div>
                                <div class="text-xl font-bold text-gray-800">$847</div>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="text-xs text-gray-600 mb-1">Transport</div>
                                <div class="text-xl font-bold text-gray-800">$234</div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">🍕 Pizza Night</span>
                                <span class="font-semibold text-gray-800">-$45.00</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">⛽ Gas Station</span>
                                <span class="font-semibold text-gray-800">-$60.00</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">☕ Coffee Shop</span>
                                <span class="font-semibold text-gray-800">-$12.50</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features section -->
<section id="features" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Features</h2>
        <p class="text-center text-gray-600 mb-12">Everything needed to track expenses, manage budgets and gain insights.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="p-6 bg-white rounded-2xl shadow-lg transform transition hover:-translate-y-2 animate-float">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="p-3 rounded-full bg-indigo-50 text-indigo-600">
                        <!-- wallet icon -->
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold">Expense Tracking</h3>
                </div>
                <p class="text-gray-600">Quickly add and categorize expenses to see where your money goes.</p>
            </div>

            <div class="p-6 bg-white rounded-2xl shadow-lg transform transition hover:-translate-y-2 animate-float" style="animation-delay:0.1s">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="p-3 rounded-full bg-indigo-50 text-indigo-600">
                        <!-- bank icon -->
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M5 10v8a2 2 0 002 2h10a2 2 0 002-2v-8M12 10V3"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold">Accounts</h3>
                </div>
                <p class="text-gray-600">Connect multiple accounts and manage balances in one place.</p>
            </div>

            <div class="p-6 bg-white rounded-2xl shadow-lg transform transition hover:-translate-y-2 animate-float" style="animation-delay:0.2s">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="p-3 rounded-full bg-indigo-50 text-indigo-600">
                        <!-- chart icon -->
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3v18M20 7l-8 8-4-4-6 6"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold">Reports & Insights</h3>
                </div>
                <p class="text-gray-600">Visualize spending patterns and export reports for deeper analysis.</p>
            </div>

            <div class="p-6 bg-white rounded-2xl shadow-lg transform transition hover:-translate-y-2 animate-float" style="animation-delay:0.3s">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="p-3 rounded-full bg-indigo-50 text-indigo-600">
                        <!-- tag icon -->
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7l10 10M7 17V7h10v10H7z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold">Categories</h3>
                </div>
                <p class="text-gray-600">Organize expenses with custom categories and rules.</p>
            </div>

            <div class="p-6 bg-white rounded-2xl shadow-lg transform transition hover:-translate-y-2 animate-float" style="animation-delay:0.4s">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="p-3 rounded-full bg-indigo-50 text-indigo-600">
                        <!-- shield icon -->
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2l8 4v6c0 5-3.6 9.74-8 10-4.4-.26-8-5-8-10V6l8-4z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold">Secure</h3>
                </div>
                <p class="text-gray-600">Data encrypted and protected with secure authentication and best practices.</p>
            </div>

            <div class="p-6 bg-white rounded-2xl shadow-lg transform transition hover:-translate-y-2 animate-float" style="animation-delay:0.5s">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="p-3 rounded-full bg-indigo-50 text-indigo-600">
                        <!-- lightning icon -->
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold">Quick Setup</h3>
                </div>
                <p class="text-gray-600">Get started fast with simple onboarding and intuitive UI.</p>
            </div>
        </div>
    </div>
</section>

</body>
</html>