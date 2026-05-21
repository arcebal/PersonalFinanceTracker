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
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme-preference="{{ $themePreference }}"
    data-font-size="{{ $fontSizePreference }}">

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
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            box-sizing: border-box;
            background-color: #8fa3b8;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            z-index: 0;
        }

        .ob-panel {
            position: relative;
            z-index: 1;
            max-width: 980px;
            width: 100%;
            display: flex;
            flex-direction: row;
            align-items: stretch;
            border-radius: 1.25rem;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(0, 0, 0, 0.45);
            animation: ob-enter 0.22s ease both;
        }

        @keyframes ob-enter {
            from {
                opacity: 0;
                transform: scale(0.97) translateY(8px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .ob-left {
            width: 42%;
            background: linear-gradient(160deg, #1a2942 0%, #243554 65%, #1e3a5f 100%);
            padding: 2.25rem 2rem;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .ob-right {
            flex: 1;
            background: #ffffff;
            padding: 2.5rem 2.25rem;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            max-height: 92vh;
        }

        .ob-circle-1 {
            position: absolute;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.04);
            top: -100px;
            right: -100px;
            pointer-events: none;
        }

        .ob-circle-2 {
            position: absolute;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.03);
            bottom: -60px;
            left: -60px;
            pointer-events: none;
        }

        .ob-step-card {
            border-radius: 0.75rem;
            padding: 0.7rem 0.9rem;
            margin-bottom: 0.45rem;
            border: 1px solid transparent;
            transition: background 0.15s;
        }

        .ob-step-card.is-current {
            background: rgba(255, 255, 255, 0.11);
            border-color: rgba(255, 255, 255, 0.14);
        }

        .ob-step-card.is-done {
            background: rgba(255, 255, 255, 0.05);
        }

        .ob-step-card.is-pending {
            background: rgba(255, 255, 255, 0.04);
        }
    </style>
</head>

<body>
    <iframe src="{{ url('/') }}" tabindex="-1" aria-hidden="true" scrolling="no"
        style="position:fixed;inset:0;width:100%;height:100%;border:none;pointer-events:none;z-index:-1;filter:blur(8px);transform:scale(1.05);"></iframe>
    <div class="ob-panel">

        {{-- LEFT PANEL --}}
        <div class="ob-left">
            <div class="ob-circle-1"></div>
            <div class="ob-circle-2"></div>

            {{-- Logo + Logout --}}
            <div
                style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;position:relative;z-index:1;">
                <a href="{{ route('onboarding.start') }}"
                    style="display:flex;align-items:center;gap:0.6rem;text-decoration:none;">
                    <span class="brand-mark"><x-application-logo class="h-6 w-6" /></span>
                    <span style="font-weight:700;font-size:0.95rem;color:#ffffff;">TrackerYarn</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        style="font-size:0.72rem;font-weight:600;color:rgba(255,255,255,0.5);background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.1);border-radius:0.5rem;padding:0.35rem 0.8rem;cursor:pointer;letter-spacing:0.01em;">Log
                        out</button>
                </form>
            </div>

            {{-- Badge + Heading + Description --}}
            <div style="margin-bottom:1.25rem;position:relative;z-index:1;">
                <p
                    style="font-size:0.58rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:rgba(255,255,255,0.38);margin-bottom:0.5rem;">
                    FIRST-RUN ONBOARDING</p>
                <h2 style="font-size:1.3rem;font-weight:700;color:#ffffff;line-height:1.3;margin-bottom:0.5rem;">
                    {{ $currentStepMeta['title'] }}</h2>
                <p style="font-size:0.76rem;color:rgba(255,255,255,0.52);line-height:1.6;">
                    {{ $currentStepMeta['aside'] }}</p>
            </div>

            {{-- Pills --}}
            <div style="display:flex;flex-wrap:wrap;gap:0.35rem;margin-bottom:1.25rem;position:relative;z-index:1;">
                <span
                    style="font-size:0.65rem;background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.55);padding:0.2rem 0.55rem;border-radius:999px;">2
                    required</span>
                <span
                    style="font-size:0.65rem;background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.55);padding:0.2rem 0.55rem;border-radius:999px;">2
                    optional</span>
                <span
                    style="font-size:0.65rem;background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.55);padding:0.2rem 0.55rem;border-radius:999px;">Resume
                    supported</span>
            </div>

            {{-- Steps --}}
            <div style="flex:1;position:relative;z-index:1;">
                @foreach ($steps as $stepKey => $stepMeta)
                    @php
                        $stepNumber = $loop->iteration;
                        $isCurrent = $currentStep === $stepKey;
                        $isComplete = $stepNumber < $currentStepNumber;
                    @endphp
                    <div
                        class="ob-step-card {{ $isCurrent ? 'is-current' : ($isComplete ? 'is-done' : 'is-pending') }}">
                        <div
                            style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.2rem;">
                            <div style="display:flex;align-items:center;gap:0.45rem;">
                                <span
                                    style="font-size:0.65rem;font-weight:700;color:{{ $isCurrent ? '#fff' : 'rgba(255,255,255,0.4)' }};background:rgba(255,255,255,0.1);min-width:1.35rem;height:1.35rem;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;">{{ $stepNumber }}</span>
                                <span
                                    style="font-size:0.78rem;font-weight:600;color:{{ $isCurrent ? '#ffffff' : 'rgba(255,255,255,0.5)' }};">{{ $stepMeta['title'] }}</span>
                                <span
                                    style="font-size:0.55rem;font-weight:700;padding:0.12rem 0.38rem;border-radius:999px;{{ $stepMeta['required'] ? 'background:rgba(52,211,153,0.15);color:#6ee7b7;' : 'background:rgba(251,191,36,0.15);color:#fcd34d;' }}">{{ $stepMeta['required'] ? 'Required' : 'Optional' }}</span>
                            </div>
                            <span
                                style="font-size:0.62rem;font-weight:600;padding:0.12rem 0.45rem;border-radius:999px;white-space:nowrap;{{ $isComplete ? 'background:rgba(52,211,153,0.15);color:#6ee7b7;' : ($isCurrent ? 'background:rgba(255,255,255,0.15);color:#fff;' : 'background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.3);') }}">
                                {{ $isComplete ? 'Done' : ($isCurrent ? 'Current' : 'Pending') }}
                            </span>
                        </div>
                        <p
                            style="font-size:0.68rem;color:rgba(255,255,255,0.38);line-height:1.45;margin-left:1.8rem;margin-bottom:0;">
                            {{ $stepMeta['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- RIGHT PANEL --}}
        <div class="ob-right">
            <div style="margin-bottom:1.5rem;">
                <span
                    style="font-size:0.68rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:rgba(15,23,42,0.38);">Step
                    {{ $currentStepNumber }} of {{ $totalSteps }}</span>
                <h1 style="font-size:1.45rem;font-weight:700;color:#0f172a;margin:0.3rem 0 0.45rem;line-height:1.25;">
                    {{ $currentStepMeta['title'] }}</h1>
                <p style="font-size:0.83rem;color:rgba(15,23,42,0.52);line-height:1.6;margin:0;">
                    {{ $currentStepMeta['description'] }}</p>
            </div>

            @if ($errors->any())
                <div class="alert-error" style="margin-bottom:1rem;">
                    {{ $errors->first() }}
                </div>
            @endif

            @yield('content')
        </div>

    </div>
</body>

</html>
