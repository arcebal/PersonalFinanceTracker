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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TrackerYarn - @yield('title', 'Onboarding')</title>
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
    <div class="auth-shell">
        <div class="auth-grid">
            <section class="auth-copy onboarding-copy">
                <div class="onboarding-copy-top">
                    <a href="{{ route('onboarding.start') }}" class="brand-lockup">
                        <span class="brand-mark">
                            <x-application-logo class="h-7 w-7" />
                        </span>
                        <span class="brand-copy text-white">
                            <strong class="text-white">TrackerYarn</strong>
                            <span class="text-white/70">First-run workspace setup</span>
                        </span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="onboarding-logout">Log out</button>
                    </form>
                </div>

                <div class="mt-12 max-w-xl">
                    <span class="auth-copy-badge">First-run onboarding</span>
                    <h1 class="auth-copy-title mt-4">{{ $currentStepMeta['title'] }}</h1>
                    <p class="auth-copy-text mt-6">{{ $currentStepMeta['aside'] }}</p>
                </div>

                <div class="onboarding-summary mt-8">
                    <span class="topbar-pill">2 required steps</span>
                    <span class="topbar-pill">2 optional steps</span>
                    <span class="topbar-pill">Resume supported</span>
                </div>

                <div class="onboarding-progress mt-8">
                    @foreach ($steps as $stepKey => $stepMeta)
                        @php
                            $stepNumber = $loop->iteration;
                            $isCurrent = $currentStep === $stepKey;
                            $isComplete = $stepNumber < $currentStepNumber;
                        @endphp
                        <article class="onboarding-progress-item {{ $isCurrent ? 'is-current' : '' }} {{ $isComplete ? 'is-complete' : '' }}">
                            <div class="onboarding-progress-index">
                                {{ $stepNumber }}
                            </div>

                            <div class="onboarding-progress-copy">
                                <div class="flex items-center gap-3">
                                    <h2>{{ $stepMeta['title'] }}</h2>
                                    <span class="status-chip {{ $stepMeta['required'] ? 'status-safe' : 'status-warning' }}">
                                        {{ $stepMeta['required'] ? 'Required' : 'Optional' }}
                                    </span>
                                </div>
                                <p>{{ $stepMeta['description'] }}</p>
                            </div>

                            <div class="onboarding-progress-state">
                                @if ($isComplete)
                                    <span class="status-chip status-safe">Done</span>
                                @elseif ($isCurrent)
                                    <span class="status-chip">Current</span>
                                @else
                                    <span class="status-chip status-muted">Pending</span>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="auth-card">
                <div class="auth-card-inner">
                    <div class="auth-heading">
                        <span class="page-kicker">Step {{ $currentStepNumber }} of {{ $totalSteps }}</span>
                        <h1>{{ $currentStepMeta['title'] }}</h1>
                        <p>{{ $currentStepMeta['description'] }}</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert-error">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </section>
        </div>
    </div>
</body>
</html>
