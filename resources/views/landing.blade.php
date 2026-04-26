<!DOCTYPE html>
@php
    $settingsUser = auth()->user();
    $themePreference = match ($settingsUser?->theme_preference ?? 'light') {
        'ember' => 'light',
        'light', 'dark', 'system', 'blue' => $settingsUser?->theme_preference ?? 'light',
        default => 'light',
    };
    $fontSizePreference = $settingsUser?->font_size_preference ?? 'default';
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme-preference="{{ $themePreference }}" data-font-size="{{ $fontSizePreference }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TrackerYarn') }} - Personal Finance Tracker</title>
    <script>
        (() => {
            const root = document.documentElement;
            const themePreference = root.dataset.themePreference || 'light';
            const fontSizePreference = root.dataset.fontSize || 'default';
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const shouldUseDark = themePreference === 'dark' || (themePreference === 'system' && prefersDark);

            root.classList.toggle('dark', shouldUseDark);
            root.dataset.themePreference = themePreference;
            root.dataset.fontSize = fontSizePreference;
        })();
    </script>
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
                    <span>Finance workspace with guided activation</span>
                </span>
            </a>

            <div class="hidden items-center gap-3 md:flex">
                <a href="#features" class="ghost-link">Features</a>
                <a href="#activation" class="ghost-link">First Run</a>
                <a href="#team" class="ghost-link">Team</a>
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
                <a href="#activation" class="btn-secondary justify-start">First Run</a>
                <a href="#team" class="btn-secondary justify-start">Team</a>
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="btn-secondary justify-start">Login</a>
                @endif
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary justify-start">Get Started</a>
                @endif
            </div>
        </div>

        <section class="landing-hero-grid">
            <div class="landing-copy landing-copy--enhanced flex flex-col justify-between">
                <div class="max-w-2xl">
                    <div class="landing-summary-pills">
                        <span class="auth-copy-badge">Hosted Finance Product</span>
                        <span class="landing-pill">First-run onboarding</span>
                        <span class="landing-pill">Recurring reminders</span>
                    </div>

                    <h1 class="auth-copy-title mt-5">From sign-up to first transaction, the product now guides users instead of leaving them in empty tables.</h1>
                    <p class="auth-copy-text mt-6 max-w-xl">
                        TrackerYarn now combines guided onboarding, category presets, monthly budgets, recurring transaction reminders, and notification previews in one calmer finance workspace.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primary">Launch your workspace</a>
                        @endif
                        <a href="#activation" class="btn-secondary">See first-run flow</a>
                    </div>
                </div>

                <div class="landing-stat-grid mt-10">
                    <div class="landing-stat landing-motion-card" style="--card-delay: 0ms;">
                        <strong>4 steps</strong>
                        <span>guided onboarding from account setup to first activity</span>
                    </div>
                    <div class="landing-stat landing-motion-card" style="--card-delay: 120ms;">
                        <strong>7 days</strong>
                        <span>ahead for recurring due item visibility and reminders</span>
                    </div>
                    <div class="landing-stat landing-motion-card" style="--card-delay: 240ms;">
                        <strong>1 inbox</strong>
                        <span>for alerts, budget pressure, and finance notifications</span>
                    </div>
                </div>
            </div>

            <div class="section-card landing-preview landing-preview--enhanced flex flex-col justify-between landing-motion-card" style="--card-delay: 160ms;">
                <div class="panel-heading">
                    <div class="panel-title-block">
                        <span class="page-kicker">Preview</span>
                        <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">Activation-ready dashboard</h2>
                        <p class="panel-subtitle">The experience starts with setup guidance, then opens into budgets, reminders, and live finance activity.</p>
                    </div>
                    <span class="app-badge">{{ now()->format('F Y') }}</span>
                </div>

                <div class="landing-preview-stack mt-6">
                    <article class="landing-preview-panel">
                        <div class="inline-meta">
                            <span class="status-chip status-safe">First-run flow</span>
                            <span class="text-sm text-muted">Required minimum setup</span>
                        </div>
                        <div class="landing-checklist mt-4">
                            <div class="landing-checklist-item is-done">
                                <span class="landing-checklist-dot"></span>
                                <span>Create first account</span>
                            </div>
                            <div class="landing-checklist-item is-done">
                                <span class="landing-checklist-dot"></span>
                                <span>Choose starter categories</span>
                            </div>
                            <div class="landing-checklist-item">
                                <span class="landing-checklist-dot"></span>
                                <span>Set first monthly budget</span>
                            </div>
                            <div class="landing-checklist-item">
                                <span class="landing-checklist-dot"></span>
                                <span>Add first transaction</span>
                            </div>
                        </div>
                    </article>

                    <div class="metric-grid">
                        <div class="metric-card col-span-12 md:col-span-6 landing-motion-card" style="--card-delay: 320ms;">
                            <div class="metric-icon">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16M7 16V9m5 7V5m5 11v-4" />
                                </svg>
                            </div>
                            <div class="metric-label">Budget pulse</div>
                            <div class="metric-value">71%</div>
                            <div class="metric-trend">Planned versus spent by category</div>
                        </div>

                        <div class="metric-card col-span-12 md:col-span-6 landing-motion-card" style="--card-delay: 420ms;">
                            <div class="metric-icon">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 4v4m10-4v4M4 10h16M6 20h12a2 2 0 002-2V8H4v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="metric-label">Recurring due</div>
                            <div class="metric-value">3</div>
                            <div class="metric-trend">Bills and repeating cash flow due this week</div>
                        </div>
                    </div>

                    <div class="table-shell">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Live modules</th>
                                    <th>Status</th>
                                    <th>Impact</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="table-title">Onboarding wizard</div>
                                        <div class="text-sm text-muted">Required minimum setup</div>
                                    </td>
                                    <td><span class="badge-income">Live</span></td>
                                    <td class="text-brand font-bold">Faster activation</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="table-title">Notification inbox</div>
                                        <div class="text-sm text-muted">Alerts and reminders</div>
                                    </td>
                                    <td><span class="badge-income">Live</span></td>
                                    <td class="text-brand font-bold">Centralized updates</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="table-title">Recurring tracking</div>
                                        <div class="text-sm text-muted">Upcoming due items</div>
                                    </td>
                                    <td><span class="badge-income">Live</span></td>
                                    <td class="text-brand font-bold">Less missed bills</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="section-card">
            <div class="page-header">
                <div class="page-title-block">
                    <span class="page-kicker">Product</span>
                    <h2 class="page-title">Finance tracking with stronger activation and better follow-through</h2>
                    <p class="page-subtitle">The public experience now reflects what the product actually does after sign-up, not just the old dashboard features.</p>
                </div>
            </div>

            <div class="feature-grid mt-8">
                <article class="feature-card landing-motion-card" style="--card-delay: 0ms;">
                    <div class="feature-icon text-brand">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3>First-run onboarding</h3>
                    <p>New users are guided through account creation, starter categories, budgets, and their first transaction instead of entering an empty app.</p>
                </article>

                <article class="feature-card landing-motion-card" style="--card-delay: 100ms;">
                    <div class="feature-icon text-brand">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.5h18v9A2.5 2.5 0 0118.5 20h-13A2.5 2.5 0 013 17.5v-9zm0 3h18M7 5h10" />
                        </svg>
                    </div>
                    <h3>Multi-account tracking</h3>
                    <p>Track cash, wallets, and e-wallet balances cleanly so every later transaction has a real home.</p>
                </article>

                <article class="feature-card landing-motion-card" style="--card-delay: 200ms;">
                    <div class="feature-icon text-brand">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 7h14M5 12h14M9 17h10" />
                        </svg>
                    </div>
                    <h3>Faster transaction logging</h3>
                    <p>Add income and expenses with category-aware forms that stay aligned with the account and onboarding setup.</p>
                </article>

                <article class="feature-card landing-motion-card" style="--card-delay: 300ms;">
                    <div class="feature-icon text-brand">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16M7 16V9m5 7V5m5 11v-4" />
                        </svg>
                    </div>
                    <h3>Monthly budget control</h3>
                    <p>Set budget targets by category and compare plan versus actual spend with clearer pressure signals.</p>
                </article>

                <article class="feature-card landing-motion-card" style="--card-delay: 400ms;">
                    <div class="feature-icon text-brand">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 4v4m10-4v4M4 10h16M6 20h12a2 2 0 002-2V8H4v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3>Recurring transaction reminders</h3>
                    <p>Watch bills and repeating income before they are due, then confirm them directly when the time arrives.</p>
                </article>

                <article class="feature-card landing-motion-card" style="--card-delay: 500ms;">
                    <div class="feature-icon text-brand">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0h6z" />
                        </svg>
                    </div>
                    <h3>Notification center</h3>
                    <p>Bring budget alerts, recurring reminders, and finance activity nudges into one inbox instead of scattering them across views.</p>
                </article>
            </div>
        </section>

        <section id="activation" class="section-card">
            <div class="page-header">
                <div class="page-title-block">
                    <span class="page-kicker">First Run</span>
                    <h2 class="page-title">What the new user journey looks like</h2>
                    <p class="page-subtitle">The hosted flow now leads users into a minimum viable setup before the rest of the app opens up.</p>
                </div>
            </div>

            <div class="landing-flow-grid mt-8">
                <article class="landing-flow-card landing-motion-card" style="--card-delay: 0ms;">
                    <span class="landing-flow-index">01</span>
                    <h3>Create first account</h3>
                    <p>Users add the first wallet or cash source so transactions and balances have a real starting point.</p>
                </article>

                <article class="landing-flow-card landing-motion-card" style="--card-delay: 120ms;">
                    <span class="landing-flow-index">02</span>
                    <h3>Choose starter categories</h3>
                    <p>Preset income and expense categories remove setup friction and make reports usable immediately.</p>
                </article>

                <article class="landing-flow-card landing-motion-card" style="--card-delay: 240ms;">
                    <span class="landing-flow-index">03</span>
                    <h3>Set first monthly budget</h3>
                    <p>Users can add spending targets right away or skip this optional step and return later.</p>
                </article>

                <article class="landing-flow-card landing-motion-card" style="--card-delay: 360ms;">
                    <span class="landing-flow-index">04</span>
                    <h3>Add first transaction</h3>
                    <p>The first entry closes the setup loop and gives the dashboard immediate live financial activity.</p>
                </article>
            </div>
        </section>

        <footer id="team" class="landing-footer">
            <div class="page-header">
                <div class="page-title-block">
                    <span class="page-kicker">Meet The Team</span>
                    <h2 class="page-title">The people behind the build</h2>
                    <p class="page-subtitle">These are the people behind this system. For their Database Subject</p>
                </div>
            </div>

            <div class="landing-team-grid mt-8">
                <article class="team-card landing-motion-card" style="--card-delay: 0ms;">
                    <div class="team-card-media">
                        <img src="{{ asset('team/john.png') }}" alt="John Clifford Ceballos photo" class="team-card-image">
                    </div>
                    <div class="team-card-body">
                        <div class="team-role">Project Manager</div>
                        <h3>John Clifford Ceballos</h3>
                    </div>
                </article>

                <article class="team-card landing-motion-card" style="--card-delay: 120ms;">
                    <div class="team-card-media">
                        <img src="{{ asset('team/argie.png') }}" alt="Argie Matondo photo" class="team-card-image">
                    </div>
                    <div class="team-card-body">
                        <div class="team-role">Hacker</div>
                        <h3>Argie Matondo</h3>
                    </div>
                </article>

                <article class="team-card landing-motion-card" style="--card-delay: 240ms;">
                    <div class="team-card-media">
                        <img src="{{ asset('team/clark2.jpeg') }}" alt="Clark Einon Estrada photo" class="team-card-image">
                    </div>
                    <div class="team-card-body">
                        <div class="team-role">Hipster</div>
                        <h3>Clark Einon Estrada</h3>
                    </div>
                </article>
            </div>
        </footer>
    </div>
</body>
</html>
