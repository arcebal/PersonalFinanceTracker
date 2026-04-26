<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TrackerYarn') }} - Personal Finance Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="landing-shell" x-data="{ mobileMenuOpen: false }">
        <nav class="landing-nav">
            <a href="/" class="brand-lockup">
                <span class="brand-mark">
                    <x-application-logo class="h-7 w-7" />
                </span>
                <span class="brand-copy">
                    <strong>TrackerYarn</strong>
                    <span>Minimal finance command center</span>
                </span>
            </a>

            <div class="hidden items-center gap-3 md:flex">
                <a href="#features" class="ghost-link">Features</a>
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="btn-secondary">Login</a>
                @endif
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary">Get Started</a>
                @endif
            </div>

            <button type="button" class="icon-button md:hidden" @click="mobileMenuOpen = !mobileMenuOpen" aria-label="Toggle menu">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
            </button>
        </nav>

        <div
            x-cloak
            x-show="mobileMenuOpen"
            x-transition
            class="mb-4 rounded-[24px] border border-[var(--border)] bg-[var(--bg-panel)] p-4 shadow-xl backdrop-blur-2xl md:hidden"
        >
            <div class="grid gap-3">
                <a href="#features" class="btn-secondary justify-start">Features</a>
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="btn-secondary justify-start">Login</a>
                @endif
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary justify-start">Get Started</a>
                @endif
            </div>
        </div>

        <section class="landing-hero-grid">
            <div class="landing-copy flex flex-col justify-between">
                <div class="max-w-2xl">
                    <span class="auth-copy-badge">Modern Finance Workspace</span>
                    <h1 class="auth-copy-title mt-5">Track every peso with a cleaner, calmer dashboard.</h1>
                    <p class="auth-copy-text mt-6 max-w-xl">
                        Manage balances, transactions, categories, and budgets inside a glassmorphism interface inspired by modern fintech products, without the clutter.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primary">Create your workspace</a>
                        @endif
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="btn-secondary">Sign in</a>
                        @endif
                    </div>
                </div>

                <div class="landing-stat-grid mt-10">
                    <div class="landing-stat">
                        <strong>1 view</strong>
                        <span>for balances, cash flow, and budgets</span>
                    </div>
                    <div class="landing-stat">
                        <strong>Fast</strong>
                        <span>entry for daily transaction logging</span>
                    </div>
                    <div class="landing-stat">
                        <strong>Clear</strong>
                        <span>category-level spending visibility</span>
                    </div>
                </div>
            </div>

            <div class="section-card landing-preview flex flex-col justify-between">
                <div class="panel-heading">
                    <div class="panel-title-block">
                        <span class="page-kicker">Preview</span>
                        <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">Monthly command center</h2>
                        <p class="panel-subtitle">A simplified overview of balance, incoming cash, and spending pressure.</p>
                    </div>
                    <span class="app-badge">{{ now()->format('F Y') }}</span>
                </div>

                <div class="metric-grid mt-6">
                    <div class="metric-card col-span-12 md:col-span-6">
                        <div class="metric-icon">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.5h18v9A2.5 2.5 0 0118.5 20h-13A2.5 2.5 0 013 17.5v-9zm0 3h18M7 5h10" />
                            </svg>
                        </div>
                        <div class="metric-label">Total balance</div>
                        <div class="metric-value">₱184,250</div>
                        <div class="metric-trend">Across wallet, cash, and bank accounts</div>
                    </div>

                    <div class="metric-card col-span-12 md:col-span-6">
                        <div class="metric-icon">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16M7 16V9m5 7V5m5 11v-4" />
                            </svg>
                        </div>
                        <div class="metric-label">Budget use</div>
                        <div class="metric-value">71%</div>
                        <div class="metric-trend">Most categories are still within target</div>
                    </div>
                </div>

                <div class="table-shell mt-6">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Recent activity</th>
                                <th>Type</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="table-title">Freelance payout</div>
                                    <div class="text-sm text-muted">Main Wallet</div>
                                </td>
                                <td><span class="badge-income">Income</span></td>
                                <td class="text-income font-bold">+ ₱26,400</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="table-title">Groceries</div>
                                    <div class="text-sm text-muted">Weekend run</div>
                                </td>
                                <td><span class="badge-expense">Expense</span></td>
                                <td class="text-expense font-bold">- ₱3,240</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="table-title">Utilities</div>
                                    <div class="text-sm text-muted">Monthly bill</div>
                                </td>
                                <td><span class="badge-expense">Expense</span></td>
                                <td class="text-expense font-bold">- ₱2,180</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section id="features" class="section-card">
            <div class="page-header">
                <div class="page-title-block">
                    <span class="page-kicker">Product</span>
                    <h2 class="page-title">Built for everyday finance tracking</h2>
                    <p class="page-subtitle">Every core workflow is streamlined around faster logging, better visibility, and cleaner organization.</p>
                </div>
            </div>

            <div class="feature-grid mt-8">
                <article class="feature-card">
                    <div class="feature-icon text-brand">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.5h18v9A2.5 2.5 0 0118.5 20h-13A2.5 2.5 0 013 17.5v-9zm0 3h18M7 5h10" />
                        </svg>
                    </div>
                    <h3>Account management</h3>
                    <p>Create multiple money buckets and keep balances organized without bouncing between pages.</p>
                </article>

                <article class="feature-card">
                    <div class="feature-icon text-brand">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 7h14M5 12h14M9 17h10" />
                        </svg>
                    </div>
                    <h3>Transaction logging</h3>
                    <p>Add income and expenses quickly with category-aware forms and cleaner table views.</p>
                </article>

                <article class="feature-card">
                    <div class="feature-icon text-brand">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16M7 16V9m5 7V5m5 11v-4" />
                        </svg>
                    </div>
                    <h3>Budget oversight</h3>
                    <p>Set monthly category targets and compare planned amounts versus actual spend with less friction.</p>
                </article>
            </div>
        </section>
    </div>
</body>
</html>
