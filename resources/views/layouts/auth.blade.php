<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TrackerYarn - @yield('title','Auth')</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body>
    <div class="auth-shell">
        <div class="auth-grid">
            <section class="auth-copy">
                <a href="/" class="brand-lockup">
                    <span class="brand-mark">
                        <x-application-logo class="h-7 w-7" />
                    </span>
                    <span class="brand-copy text-white">
                        <strong class="text-white">TrackerYarn</strong>
                        <span class="text-white/70">Glass finance dashboard</span>
                    </span>
                </a>

                <div class="mt-12 max-w-xl">
                    <span class="auth-copy-badge">@yield('title', 'Auth')</span>
                    <h1 class="auth-copy-title mt-4">Less clutter, faster decisions, and a calmer finance workflow.</h1>
                    <p class="auth-copy-text mt-6">
                        The new workspace is built around clean glass surfaces, sharper hierarchy, and faster daily actions for accounts, budgets, and transaction tracking.
                    </p>
                </div>

                <div class="mt-10 grid gap-4 md:grid-cols-2">
                    <div class="rounded-[24px] border border-white/15 bg-white/10 p-5 backdrop-blur-xl">
                        <div class="table-eyebrow text-white/70">Dashboard</div>
                        <div class="mt-2 text-2xl font-extrabold">Live trends</div>
                        <p class="mt-2 text-sm text-white/75">Monitor income, expense, and balance movement from a single view.</p>
                    </div>
                    <div class="rounded-[24px] border border-white/15 bg-white/10 p-5 backdrop-blur-xl">
                        <div class="table-eyebrow text-white/70">Controls</div>
                        <div class="mt-2 text-2xl font-extrabold">Quick actions</div>
                        <p class="mt-2 text-sm text-white/75">Jump directly into new transactions, accounts, and category management.</p>
                    </div>
                </div>
            </section>

            <section class="auth-card">
                <div class="auth-card-inner">
                    <div class="auth-heading">
                        <span class="page-kicker">Account</span>
                        <h1>@yield('title', 'Auth')</h1>
                        <p>@yield('subtitle', 'Access your personal finance workspace securely.') </p>
                    </div>

                    @yield('content')
                </div>
            </section>
        </div>
    </div>
</body>
</html>
