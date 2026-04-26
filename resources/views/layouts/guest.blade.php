<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TrackerYarn') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                            <span class="text-white/70">Clearer money decisions</span>
                        </span>
                    </a>

                    <div class="mt-12 max-w-xl">
                        <span class="auth-copy-badge">Secure Access</span>
                        <h1 class="auth-copy-title mt-4">Your finances should feel organized before you even sign in.</h1>
                        <p class="auth-copy-text mt-6">
                            Manage accounts, categories, budgets, and transaction history inside a cleaner glass workspace built to feel more like a modern fintech product.
                        </p>
                    </div>

                    <div class="mt-10 grid gap-4 md:grid-cols-2">
                        <div class="rounded-[24px] border border-white/15 bg-white/10 p-5 backdrop-blur-xl">
                            <div class="table-eyebrow text-white/70">Planning</div>
                            <div class="mt-2 text-2xl font-extrabold">Budgets</div>
                            <p class="mt-2 text-sm text-white/75">Set monthly targets and compare actual spend without leaving the dashboard.</p>
                        </div>
                        <div class="rounded-[24px] border border-white/15 bg-white/10 p-5 backdrop-blur-xl">
                            <div class="table-eyebrow text-white/70">Visibility</div>
                            <div class="mt-2 text-2xl font-extrabold">Insights</div>
                            <p class="mt-2 text-sm text-white/75">Review category breakdowns and recent cash movement in one place.</p>
                        </div>
                    </div>
                </section>

                <section class="auth-card">
                    <div class="auth-card-inner">
                        <div class="auth-heading">
                            <span class="page-kicker">Account Access</span>
                            <h1>Continue to your workspace</h1>
                            <p>Authenticate securely to manage your balances, budgets, and transactions.</p>
                        </div>

                        {{ $slot }}
                    </div>
                </section>
            </div>
        </div>
    </body>
</html>
