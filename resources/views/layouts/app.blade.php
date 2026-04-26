<!DOCTYPE html>
@php
    $settingsUser = auth()->user();
    $themePreference = match ($settingsUser?->theme_preference ?? 'light') {
        'ember' => 'light',
        'light', 'dark', 'system', 'blue' => $settingsUser?->theme_preference ?? 'light',
        default => 'light',
    };
    $fontSizePreference = $settingsUser?->font_size_preference ?? 'default';
    $unreadNotificationCount = $settingsUser ? $settingsUser->appNotifications()->whereNull('read_at')->count() : 0;
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme-preference="{{ $themePreference }}" data-font-size="{{ $fontSizePreference }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} – @yield('title', 'Dashboard')</title>
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
<body x-data="{ sidebarOpen: false }">
    @php
        $pageTitle = trim($__env->yieldContent('title')) ?: 'Dashboard';
    @endphp

    <div class="app-shell">
        <div
            x-cloak
            x-show="sidebarOpen"
            class="fixed inset-0 z-30 bg-slate-950/45 backdrop-blur-sm lg:hidden"
            @click="sidebarOpen = false"
        ></div>

        <div
            class="app-sidebar fixed inset-y-0 left-0 z-40 w-[19rem] p-4 transition-transform duration-300 lg:w-auto lg:p-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            @include('layouts.sidebar')
        </div>

        <div class="shell-main lg:ml-0">
            <header class="topbar">
                <div class="flex items-center gap-3">
                    <button type="button" class="icon-button lg:hidden" @click="sidebarOpen = true" aria-label="Open sidebar">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
                        </svg>
                    </button>
                    <div class="topbar-meta">
                        <span class="topbar-label">Finance Workspace</span>
                        <span class="topbar-title">{{ $pageTitle }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-3 self-stretch sm:self-auto">
                    <span class="topbar-pill">{{ now()->format('D, M j') }}</span>
                    <a href="{{ route('notifications.index') }}" class="topbar-pill notification-pill">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0h6z" />
                        </svg>
                        <span>Notifications</span>
                        @if ($unreadNotificationCount > 0)
                            <span class="notification-pill-count">{{ $unreadNotificationCount }}</span>
                        @endif
                    </a>
                </div>
            </header>

            <main class="content-shell">
                @if(session('success'))
                    <div id="flash" data-success="{{ session('success') }}" data-undo="{{ session('undo') ?? '' }}" class="hidden"></div>
                @endif

                @if ($errors->any())
                    <div class="alert-error">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (isset($header))
                    <section class="section-card">
                        {{ $header }}
                    </section>
                @endif

                @yield('content')
                {{ $slot ?? '' }}
            </main>
        </div>
    </div>

    @yield('scripts')
</body>
</html>
