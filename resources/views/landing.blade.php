<!DOCTYPE html>
@php
    $settingsUser = auth()->user();
    $themePreference = match ($settingsUser?->theme_preference ?? 'light') {
        'ember' => 'light',
        'light', 'dark', 'system', 'blue' => $settingsUser?->theme_preference ?? 'light',
        default => 'light',
    };
    $fontSizePreference = $settingsUser?->font_size_preference ?? 'default';
    $teamMembers = [
        'john' => [
            'name' => 'John Clifford Ceballos',
            'role' => 'Project Manager',
            'image' => asset('team/john.png'),
            'image_alt' => 'John Clifford Ceballos photo',
            'facebook_url' => 'https://www.facebook.com/share/18gW7HUnkC/',
            'instagram_url' => null,
            'email' => null,
            'summary' =>
                'Keeps the build aligned by coordinating scope, product direction, and delivery across the team.',
        ],
        'argie' => [
            'name' => 'Argie Matondo',
            'role' => 'Hacker',
            'image' => asset('team/argie.png'),
            'image_alt' => 'Argie Matondo photo',
            'facebook_url' => 'https://www.facebook.com/share/18qrdwx6cd/',
            'instagram_url' => null,
            'email' => null,
            'summary' => 'Focuses on implementation details and technical problem-solving to keep the product moving.',
        ],
        'clark' => [
            'name' => 'Clark Einon Estrada',
            'role' => 'Hipster',
            'image' => asset('team/clark2.png'),
            'image_alt' => 'Clark Einon Estrada photo',
            'facebook_url' => 'https://www.facebook.com/share/1EogzsrRhU/',
            'instagram_url' => null,
            'email' => null,
            'summary' => 'Shapes the look and feel of the experience so the product stays polished and approachable.',
        ],
    ];
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme-preference="{{ $themePreference }}"
    data-font-size="{{ $fontSizePreference }}">

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
    <div class="lp-shell" x-data="{
        mobileMenuOpen: false,
        loginOpen: {{ $errors->has('email') || $errors->has('password') ? 'true' : 'false' }},
        registerOpen: {{ $errors->has('name') || $errors->has('password_confirmation') ? 'true' : 'false' }},
        activeTeamKey: null,
        lastTeamTrigger: null,
        teamMembers: {{ \Illuminate\Support\Js::from($teamMembers) }},
        get activeTeam() {
            return this.activeTeamKey ? this.teamMembers[this.activeTeamKey] : null;
        },
        openTeam(key, trigger) {
            this.activeTeamKey = key;
            this.lastTeamTrigger = trigger;
            document.documentElement.style.overflow = 'hidden';
            document.body.style.overflow = 'hidden';
            this.$nextTick(() => this.$refs.teamModalClose?.focus());
        },
        closeTeam() {
            this.activeTeamKey = null;
            document.documentElement.style.overflow = '';
            document.body.style.overflow = '';
            this.$nextTick(() => this.lastTeamTrigger?.focus());
        }
    }" @keydown.escape.window="activeTeamKey && closeTeam()">

        {{-- NAV --}}
        <header class="lp-nav">
            <div class="lp-container lp-nav-inner">
                <a href="/" class="brand-lockup">
                    <span class="brand-mark">
                        <x-application-logo class="h-7 w-7" />
                    </span>
                    <span class="brand-copy">
                        <strong>TrackerYarn</strong>
                    </span>
                </a>

                <div class="lp-nav-links">
                    <div class="lp-nav-center">
                        <a href="#features" class="ghost-link">Features</a>
                        <a href="#steps" class="ghost-link">First Run</a>
                        <a href="#team" class="ghost-link">Team</a>
                    </div>
                    <div class="lp-nav-right">
                        @if (Route::has('login'))
                            <button type="button" @click="loginOpen = true" class="btn-secondary">Login</button>
                        @endif
                        @if (Route::has('register'))
                            <button type="button" @click="registerOpen = true" class="btn-primary">Get Started</button>
                        @endif
                    </div>
                </div>

                <button type="button" class="icon-button lp-mobile-toggle" @click="mobileMenuOpen = !mobileMenuOpen"
                    aria-label="Toggle menu">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
                    </svg>
                </button>
            </div>

            <div x-cloak x-show="mobileMenuOpen" x-transition class="lp-mobile-menu">
                <a href="#features" class="btn-secondary" @click="mobileMenuOpen=false">Features</a>
                <a href="#steps" class="btn-secondary" @click="mobileMenuOpen=false">First Run</a>
                <a href="#team" class="btn-secondary" @click="mobileMenuOpen=false">Team</a>
                @if (Route::has('login'))
                    <button type="button" @click="loginOpen = true; mobileMenuOpen = false"
                        class="btn-secondary">Login</button>
                @endif
                @if (Route::has('register'))
                    <button type="button" @click="registerOpen = true; mobileMenuOpen = false" class="btn-primary">Get
                        Started</button>
                @endif
            </div>
        </header>

        {{-- HERO --}}
        <style>
            /* Bold navy-to-slate gradient — matches TrackerYarn brand */
            .lp-hero-gradient {
                background: linear-gradient(165deg, #1e3a5f 0%, #243554 35%, #1a2942 70%, #0f172a 100%);
                position: relative;
                overflow: hidden;
                border-bottom: none;
            }

            /* Soft ambient glow blobs */
            .lp-hero-gradient::before {
                content: '';
                position: absolute;
                width: 500px;
                height: 500px;
                border-radius: 50%;
                background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, transparent 65%);
                top: -150px;
                right: -100px;
                pointer-events: none;
                z-index: 0;
            }

            .lp-hero-gradient::after {
                content: '';
                position: absolute;
                width: 400px;
                height: 400px;
                border-radius: 50%;
                background: radial-gradient(circle, rgba(99, 102, 241, 0.08) 0%, transparent 60%);
                bottom: -100px;
                left: -80px;
                pointer-events: none;
                z-index: 0;
            }

            .lp-hero-gradient>* {
                position: relative;
                z-index: 1;
            }

            /* YNAB-style floating animations */
            @keyframes float {

                0%,
                100% {
                    transform: translateY(0px) rotate(0deg);
                }

                50% {
                    transform: translateY(-18px) rotate(0.5deg);
                }
            }

            @keyframes float-slow {

                0%,
                100% {
                    transform: translateY(0px) rotate(0deg);
                }

                50% {
                    transform: translateY(-12px) rotate(-0.5deg);
                }
            }

            @keyframes float-reverse {

                0%,
                100% {
                    transform: translateY(0px);
                }

                50% {
                    transform: translateY(14px);
                }
            }

            .float {
                animation: float 6s ease-in-out infinite;
            }

            .float-slow {
                animation: float-slow 8s ease-in-out infinite;
            }

            .float-reverse {
                animation: float-reverse 7s ease-in-out infinite;
            }

            .float-delay-1 {
                animation-delay: 0s;
            }

            .float-delay-2 {
                animation-delay: 2s;
            }

            .float-delay-3 {
                animation-delay: 4s;
            }
        </style>
        <section class="lp-hero lp-hero-gradient">
            <div class="lp-hero-inner" style="position: relative; z-index: 1;">
                <div class="flex flex-col-reverse lg:flex-row items-center gap-0 w-full">

                    {{-- LEFT: Text Content --}}
                    <div class="w-full lg:w-1/2 flex flex-col items-start text-left pl-0 lg:pl-4">

                        <h1 class="lp-hero-title" style="color: #ffffff;">
                            Know where your<br>money goes.
                        </h1>

                        <p class="lp-hero-sub" style="color: rgba(255,255,255,0.75);">
                            Most people lose track without realizing it. TrackerYarn gives you guided setup,
                            smart categories, monthly budgets, and recurring reminders — so every peso is
                            accounted for, every single day.
                        </p>

                        <div class="lp-hero-cta">
                            @if (Route::has('register'))
                                <button type="button" @click="registerOpen = true" class="lp-btn-white">
                                    Start for free
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            @endif
                        </div>

                    </div>

                    {{-- RIGHT: Hero Image --}}
                    <div class="w-full lg:w-1/2 flex items-center justify-end">
                        <img src="{{ asset('hero/hero-section-image.png') }}"
                            alt="Personal finance management illustration"
                            class="w-full object-contain select-none float float-delay-1"
                            style="filter: drop-shadow(0 24px 48px rgba(26,41,66,0.15)); max-width: 720px; margin-right: -7rem;"
                            draggable="false">
                    </div>

                </div>
            </div>
            {{-- Wave divider --}}
            <div class="hero-wave"
                style="position: absolute; bottom: -1px; left: 0; width: 100%; overflow: hidden; line-height: 0; z-index: 2; transform: rotate(180deg);">
                <svg viewBox="0 0 1200 120" preserveAspectRatio="none"
                    style="position: relative; display: block; width: calc(100% + 1.3px); height: 100px;">
                    <path
                        d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"
                        style="fill: #f8fafc;"></path>
                </svg>
            </div>
        </section>

        {{-- FEATURES --}}
        <section id="features" class="lp-section lp-section--light">
            <div class="lp-container">
                <div class="lp-section-header">
                    <p class="lp-kicker">Product</p>
                    <h2 class="lp-title">Everything you need.<br>Nothing you don't.</h2>
                    <p class="lp-subtitle">Take control of your money without the overwhelm of complex tools or empty
                        dashboards.</p>
                </div>

                <div class="lp-feature-grid">
                    <div class="lp-feature-card landing-motion-card" style="--card-delay: 0ms;">
                        <div class="lp-feature-icon">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6l4 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="lp-feature-title">First-run onboarding</h3>
                        <p class="lp-feature-copy">Guided setup through account creation, categories, budgets, and your
                            first transaction — in minutes.</p>
                    </div>

                    <div class="lp-feature-card landing-motion-card" style="--card-delay: 80ms;">
                        <div class="lp-feature-icon">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 8.5h18v9A2.5 2.5 0 0118.5 20h-13A2.5 2.5 0 013 17.5v-9zm0 3h18M7 5h10" />
                            </svg>
                        </div>
                        <h3 class="lp-feature-title">Multi-account tracking</h3>
                        <p class="lp-feature-copy">Track cash, wallets, and e-wallet balances in one place. Know your
                            total picture at a glance.</p>
                    </div>

                    <div class="lp-feature-card landing-motion-card" style="--card-delay: 160ms;">
                        <div class="lp-feature-icon">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 19h16M7 16V9m5 7V5m5 11v-4" />
                            </svg>
                        </div>
                        <h3 class="lp-feature-title">Monthly budget control</h3>
                        <p class="lp-feature-copy">Set category spending targets and watch plan versus actual spend
                            update in real time.</p>
                    </div>

                    <div class="lp-feature-card landing-motion-card" style="--card-delay: 240ms;">
                        <div class="lp-feature-icon">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 4v4m10-4v4M4 10h16M6 20h12a2 2 0 002-2V8H4v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="lp-feature-title">Recurring reminders</h3>
                        <p class="lp-feature-copy">See upcoming bills and repeating income 7 days ahead — never miss a
                            due date again.</p>
                    </div>

                    <div class="lp-feature-card landing-motion-card" style="--card-delay: 320ms;">
                        <div class="lp-feature-icon">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0h6z" />
                            </svg>
                        </div>
                        <h3 class="lp-feature-title">Notification center</h3>
                        <p class="lp-feature-copy">Budget alerts, reminders, and activity nudges — all in one inbox so
                            nothing slips through.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- STEPS --}}
        <section id="steps" class="lp-section lp-section--tinted">
            <div class="lp-container">
                <div class="lp-section-header">
                    <p class="lp-kicker">First Run</p>
                    <h2 class="lp-title">Up and running in 4 steps.</h2>
                    <p class="lp-subtitle">We guide you through every screen. No empty tables. No guesswork.</p>
                </div>

                <div class="lp-steps-grid">
                    <div class="lp-step-card landing-motion-card" style="--card-delay: 0ms;">
                        <div class="lp-step-num">01</div>
                        <h3 class="lp-step-title">Create first account</h3>
                        <p class="lp-step-copy">Add your first wallet or cash source. Takes about 30 seconds.</p>
                    </div>
                    <div class="lp-step-card landing-motion-card" style="--card-delay: 120ms;">
                        <div class="lp-step-num">02</div>
                        <h3 class="lp-step-title">Choose starter categories</h3>
                        <p class="lp-step-copy">Preset income and expense categories, ready to use immediately.</p>
                    </div>
                    <div class="lp-step-card landing-motion-card" style="--card-delay: 240ms;">
                        <div class="lp-step-num">03</div>
                        <h3 class="lp-step-title">Set monthly budget</h3>
                        <p class="lp-step-copy">Add spending targets now, or skip and come back later.</p>
                    </div>
                    <div class="lp-step-card landing-motion-card" style="--card-delay: 360ms;">
                        <div class="lp-step-num">04</div>
                        <h3 class="lp-step-title">Add first transaction</h3>
                        <p class="lp-step-copy">Close the loop. Your dashboard now shows real activity.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="lp-section lp-section--cta">
            <div class="lp-container">
                <div class="lp-cta-inner">
                    <p class="lp-kicker">Get Started</p>
                    <h2 class="lp-title">Stop guessing.<br>Start knowing.</h2>
                    <p class="lp-subtitle">TrackerYarn walks you through every step — from your first account to your
                        first budget. Join for free today.</p>
                    <div class="lp-cta-buttons">
                        @if (Route::has('register'))
                            <button type="button" @click="registerOpen = true" class="lp-btn-white">
                                Start for free
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                                </a>
                        @endif
                        @if (Route::has('login'))
                            <button type="button" @click="loginOpen = true" class="lp-btn-ghost">Sign in
                                instead</button>
                        @endif
                    </div>
                    <p class="lp-cta-footnote">Free · No credit card required · Guided setup included</p>
                </div>
            </div>
        </section>

        {{-- TEAM --}}
        <section id="team" class="lp-section lp-section--navy">
            <div class="lp-container">
                <div class="lp-section-header">
                    <p class="lp-kicker" style="color: rgba(255,255,255,0.55);">Meet The Team</p>
                    <h2 class="lp-title" style="color: #ffffff;">The people behind the build</h2>
                    <p class="lp-subtitle" style="color: rgba(241,245,249,0.7);">Built with care by a small team
                        passionate about personal finance.</p>
                </div>

                <div class="lp-team-box">
                    <div class="lp-team-grid">
                        @foreach ($teamMembers as $teamKey => $member)
                            <button type="button" class="team-card team-card-trigger landing-motion-card"
                                style="--card-delay: {{ $loop->index * 120 }}ms;"
                                @click="openTeam('{{ $teamKey }}', $event.currentTarget)"
                                :aria-expanded="activeTeamKey === '{{ $teamKey }}'"
                                aria-controls="team-profile-modal">
                                <span class="team-card-media">
                                    <img src="{{ $member['image'] }}" alt="{{ $member['image_alt'] }}"
                                        class="team-card-image">
                                </span>
                                <span class="team-card-body">
                                    <span class="team-role">{{ $member['role'] }}</span>
                                    <h3>{{ $member['name'] }}</h3>
                                    <span class="team-card-summary">{{ $member['summary'] }}</span>
                                    <span class="team-card-hint">
                                        <span>Open profile</span>
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </span>
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- FOOTER BAR --}}
        <div class="lp-footer-bar">
            <div class="lp-container">
                <p>© {{ date('Y') }} TrackerYarn &middot; Personal finance, guided</p>
            </div>
        </div>

        {{-- LOGIN MODAL --}}
        <div x-cloak x-show="loginOpen" class="lp-modal-backdrop" @click.self="loginOpen = false"
            @keydown.escape.window="loginOpen = false" x-transition.opacity>
            <div class="lp-modal-panel"
                style="max-width:800px;width:100%;display:flex;flex-direction:row;align-items:stretch;padding:0;overflow:hidden;border-radius:1.25rem;"
                x-transition.scale.origin.top.duration.200ms>

                {{-- LEFT BRANDING PANEL --}}
                <div class="hidden sm:flex flex-col"
                    style="width:42%;background:linear-gradient(160deg,#1a2942 0%,#243554 65%,#1e3a5f 100%);padding:2.5rem 2rem;position:relative;overflow:hidden;">
                    <div
                        style="position:absolute;width:260px;height:260px;border-radius:50%;background:rgba(255,255,255,0.04);top:-80px;right:-80px;pointer-events:none;">
                    </div>
                    <div
                        style="position:absolute;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,0.03);bottom:-50px;left:-50px;pointer-events:none;">
                    </div>

                    <div style="display:flex;align-items:center;gap:0.6rem;">
                        <span class="brand-mark"><x-application-logo class="h-6 w-6" /></span>
                        <span style="font-weight:700;font-size:0.95rem;color:#ffffff;">TrackerYarn</span>
                    </div>

                    <div style="margin-top:2rem;margin-bottom:1.5rem;">
                        <p
                            style="font-size:0.6rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:rgba(255,255,255,0.4);margin-bottom:0.6rem;">
                            LOGIN</p>
                        <h3
                            style="font-size:1.45rem;font-weight:700;color:#ffffff;line-height:1.25;margin-bottom:0.75rem;">
                            Every peso.<br>Accounted for.</h3>
                        <p style="font-size:0.78rem;color:rgba(255,255,255,0.55);line-height:1.65;">Pick up where you
                            left off — your balances, budgets, and transactions are ready.</p>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:0.6rem;">
                        <div style="background:rgba(255,255,255,0.07);border-radius:0.75rem;padding:0.85rem 1rem;">
                            <p
                                style="font-size:0.58rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.35);margin-bottom:0.25rem;">
                                DASHBOARD</p>
                            <p style="font-size:0.82rem;font-weight:600;color:#ffffff;margin-bottom:0.15rem;">Live
                                overview</p>
                            <p style="font-size:0.73rem;color:rgba(255,255,255,0.5);line-height:1.5;">Balances,
                                budgets, and spending trends at a glance.</p>
                        </div>
                        <div style="background:rgba(255,255,255,0.07);border-radius:0.75rem;padding:0.85rem 1rem;">
                            <p
                                style="font-size:0.58rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.35);margin-bottom:0.25rem;">
                                TRANSACTIONS</p>
                            <p style="font-size:0.82rem;font-weight:600;color:#ffffff;margin-bottom:0.15rem;">Quick log
                            </p>
                            <p style="font-size:0.73rem;color:rgba(255,255,255,0.5);line-height:1.5;">Record income or
                                expenses in just a few taps.</p>
                        </div>
                    </div>
                </div>

                {{-- RIGHT FORM PANEL --}}
                <div style="flex:1;padding:2.5rem 2rem;display:flex;flex-direction:column;position:relative;">
                    <button type="button" class="lp-modal-close" @click="loginOpen = false" aria-label="Close">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <h2 class="lp-modal-title">Sign in to continue</h2>
                    <p class="lp-modal-sub">Enter your credentials to access your dashboard</p>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="lp-modal-form">
                        @csrf
                        <div class="lp-modal-field">
                            <label for="login_email" class="lp-modal-label">Email</label>
                            <input id="login_email" type="email" name="email" value="{{ old('email') }}"
                                class="lp-modal-input" placeholder="Enter your email address" autocomplete="username"
                                autofocus>
                            @error('email')
                                <p class="lp-modal-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="lp-modal-field">
                            <div class="flex items-center justify-between mb-1">
                                <label for="login_password" class="lp-modal-label">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="lp-modal-forgot">Forgot
                                        password?</a>
                                @endif
                            </div>
                            <input id="login_password" type="password" name="password" class="lp-modal-input"
                                placeholder="Enter your password" autocomplete="current-password">
                            @error('password')
                                <p class="lp-modal-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="lp-modal-submit">Sign in</button>
                        <p class="lp-modal-switch">
                            Don't have an account?
                            <button type="button" @click="loginOpen = false; registerOpen = true"
                                class="lp-modal-switch-link">Create one</button>
                        </p>
                    </form>
                </div>

            </div>
        </div>

        {{-- REGISTER MODAL --}}
        <div x-cloak x-show="registerOpen" class="lp-modal-backdrop" @click.self="registerOpen = false"
            @keydown.escape.window="registerOpen = false" x-transition.opacity>
            <div class="lp-modal-panel"
                style="max-width:800px;width:100%;display:flex;flex-direction:row;align-items:stretch;padding:0;overflow:hidden;border-radius:1.25rem;"
                x-transition.scale.origin.top.duration.200ms>

                {{-- LEFT BRANDING PANEL --}}
                <div class="hidden sm:flex flex-col"
                    style="width:42%;background:linear-gradient(160deg,#1a2942 0%,#243554 65%,#1e3a5f 100%);padding:2.5rem 2rem;position:relative;overflow:hidden;min-height:100%;">
                    <div
                        style="position:absolute;width:260px;height:260px;border-radius:50%;background:rgba(255,255,255,0.04);top:-80px;right:-80px;pointer-events:none;">
                    </div>
                    <div
                        style="position:absolute;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,0.03);bottom:-50px;left:-50px;pointer-events:none;">
                    </div>

                    <div style="display:flex;align-items:center;gap:0.6rem;">
                        <span class="brand-mark"><x-application-logo class="h-6 w-6" /></span>
                        <span style="font-weight:700;font-size:0.95rem;color:#ffffff;">TrackerYarn</span>
                    </div>

                    <div style="margin-top:2rem;margin-bottom:1.5rem;">
                        <p
                            style="font-size:0.6rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:rgba(255,255,255,0.4);margin-bottom:0.6rem;">
                            SIGN UP</p>
                        <h3
                            style="font-size:1.45rem;font-weight:700;color:#ffffff;line-height:1.25;margin-bottom:0.75rem;">
                            Take control<br>in minutes.</h3>
                        <p style="font-size:0.78rem;color:rgba(255,255,255,0.55);line-height:1.65;">A guided setup
                            walks you through accounts, categories, and your first budget — no guesswork.</p>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:0.6rem;">
                        <div style="background:rgba(255,255,255,0.07);border-radius:0.75rem;padding:0.85rem 1rem;">
                            <p
                                style="font-size:0.58rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.35);margin-bottom:0.25rem;">
                                ONBOARDING</p>
                            <p style="font-size:0.82rem;font-weight:600;color:#ffffff;margin-bottom:0.15rem;">Guided
                                setup</p>
                            <p style="font-size:0.73rem;color:rgba(255,255,255,0.5);line-height:1.5;">4 steps from
                                sign-up to your first real transaction.</p>
                        </div>
                        <div style="background:rgba(255,255,255,0.07);border-radius:0.75rem;padding:0.85rem 1rem;">
                            <p
                                style="font-size:0.58rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.35);margin-bottom:0.25rem;">
                                CATEGORIES</p>
                            <p style="font-size:0.82rem;font-weight:600;color:#ffffff;margin-bottom:0.15rem;">Ready to
                                use</p>
                            <p style="font-size:0.73rem;color:rgba(255,255,255,0.5);line-height:1.5;">Preset income and
                                expense categories, no setup needed.</p>
                        </div>
                    </div>
                </div>

                {{-- RIGHT FORM PANEL --}}
                <div style="flex:1;padding:2.5rem 2rem;display:flex;flex-direction:column;position:relative;">
                    <button type="button" class="lp-modal-close" @click="registerOpen = false" aria-label="Close">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <h2 class="lp-modal-title">Create your account</h2>
                    <p class="lp-modal-sub">Start tracking your finances in minutes</p>

                    <form method="POST" action="{{ route('register') }}" class="lp-modal-form">
                        @csrf
                        <div class="lp-modal-field">
                            <label for="reg_name" class="lp-modal-label">Name</label>
                            <input id="reg_name" type="text" name="name" value="{{ old('name') }}"
                                class="lp-modal-input" placeholder="Enter your full name" autocomplete="name">
                            @error('name')
                                <p class="lp-modal-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="lp-modal-field">
                            <label for="reg_email" class="lp-modal-label">Email</label>
                            <input id="reg_email" type="email" name="email" value="{{ old('email') }}"
                                class="lp-modal-input" placeholder="Enter your email address"
                                autocomplete="username">
                            @error('email')
                                <p class="lp-modal-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="lp-modal-field">
                            <label for="reg_password" class="lp-modal-label">Password</label>
                            <input id="reg_password" type="password" name="password" class="lp-modal-input"
                                placeholder="Enter your password" autocomplete="new-password">
                            @error('password')
                                <p class="lp-modal-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="lp-modal-field">
                            <label for="reg_password_confirmation" class="lp-modal-label">Confirm Password</label>
                            <input id="reg_password_confirmation" type="password" name="password_confirmation"
                                class="lp-modal-input" placeholder="Confirm your password"
                                autocomplete="new-password">
                            @error('password_confirmation')
                                <p class="lp-modal-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="lp-modal-submit">Create account</button>
                        <p class="lp-modal-switch">
                            Already have an account?
                            <button type="button" @click="registerOpen = false; loginOpen = true"
                                class="lp-modal-switch-link">Sign in</button>
                        </p>
                    </form>
                </div>

            </div>
        </div>

        {{-- TEAM MODAL — untouched --}}
        <div x-cloak x-show="activeTeam" id="team-profile-modal" class="team-modal-backdrop" role="dialog"
            aria-modal="true" aria-labelledby="team-modal-title" @click.self="closeTeam()" x-transition.opacity>
            <div class="team-modal-panel" x-transition.scale.origin.bottom.duration.250ms>
                <button type="button" class="team-modal-close" @click="closeTeam()" x-ref="teamModalClose"
                    aria-label="Close team profile">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18" />
                    </svg>
                </button>
                <template x-if="activeTeam">
                    <div class="team-modal-layout">
                        <div class="team-modal-media">
                            <img :src="activeTeam.image" :alt="activeTeam.image_alt" class="team-modal-image">
                        </div>
                        <div class="team-modal-content">
                            <span class="team-role" x-text="activeTeam.role"></span>
                            <h3 id="team-modal-title" class="team-modal-title" x-text="activeTeam.name"></h3>
                            <p class="team-modal-copy" x-text="activeTeam.summary"></p>
                            <div class="team-social-grid" x-show="activeTeam?.facebook_url">
                                <a :href="activeTeam.facebook_url" class="team-social-link" target="_blank"
                                    rel="noopener noreferrer">
                                    <span class="team-social-icon" aria-hidden="true">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M13.5 21v-7h2.4l.4-3h-2.8V9.1c0-.9.2-1.6 1.5-1.6H16V4.8c-.4 0-.9-.1-1.8-.1-2.7 0-4.2 1.6-4.2 4.4V11H7.5v3H10v7h3.5z" />
                                        </svg>
                                    </span>
                                    <span class="team-social-copy">
                                        <span class="team-social-label">Facebook</span>
                                        <span class="team-social-status">Live now</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

    </div>
</body>

</html>
