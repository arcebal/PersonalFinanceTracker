@extends('layouts.app')

@section('title','Settings')

@section('content')
@php
    $avatarUrl = $user->avatarUrl();
    $selectedThemePreference = match ($user->theme_preference ?? 'light') {
        'ember' => 'light',
        'light', 'dark', 'system', 'blue' => $user->theme_preference ?? 'light',
        default => 'light',
    };
    $themeOptions = [
        'light' => [
            'label' => 'Pink',
            'note' => 'Use the warm pink glass workspace across the whole product.',
            'preview' => 'settings-theme-preview--light',
        ],
        'dark' => [
            'label' => 'Dark',
            'note' => 'Always use the darker glass workspace.',
            'preview' => 'settings-theme-preview--dark',
        ],
        'system' => [
            'label' => 'System',
            'note' => 'Follow your device preference for dark mode.',
            'preview' => 'settings-theme-preview--system',
        ],
        'blue' => [
            'label' => 'Blue',
            'note' => 'Switch back to the original cool blue glass palette.',
            'preview' => 'settings-theme-preview--blue',
        ],
    ];
@endphp
<div class="page-shell">
    <section class="section-card">
        <div class="page-header">
            <div class="page-title-block">
                <span class="page-kicker">Settings</span>
                <h1 class="page-title">Workspace settings</h1>
                <p class="page-subtitle">Manage display preferences and your profile picture from one account-level settings screen.</p>
            </div>
            <div class="page-actions">
                <a href="{{ route('profile.edit') }}" class="btn-secondary">Go to profile</a>
            </div>
        </div>
    </section>

    <section class="settings-grid">
        <article class="settings-panel">
            <div class="panel-heading">
                <div class="panel-title-block">
                    <span class="page-kicker">Appearance</span>
                    <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">Display preferences</h2>
                    <p class="panel-subtitle">These settings follow your account across devices after you sign in.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('settings.appearance.update') }}" class="auth-form mt-6">
                @csrf
                @method('PATCH')

                <div class="settings-group">
                    <div>
                        <h3 class="settings-group-title">Theme</h3>
                        <p class="field-note">Choose how the workspace should appear across the app.</p>
                    </div>

                    <div class="settings-choice-grid settings-choice-grid--themes">
                        @foreach ($themeOptions as $value => $themeOption)
                            <label class="settings-choice-card">
                                <input
                                    type="radio"
                                    name="theme_preference"
                                    value="{{ $value }}"
                                    class="settings-choice-input"
                                    {{ old('theme_preference', $selectedThemePreference) === $value ? 'checked' : '' }}
                                >
                                <span class="settings-choice-copy">
                                    <span class="settings-theme-preview {{ $themeOption['preview'] }}" aria-hidden="true">
                                        <span class="settings-theme-preview-swatch"></span>
                                        <span class="settings-theme-preview-swatch"></span>
                                        <span class="settings-theme-preview-swatch"></span>
                                    </span>
                                    <span class="settings-choice-title">{{ $themeOption['label'] }}</span>
                                    <span class="settings-choice-note">
                                        {{ $themeOption['note'] }}
                                    </span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('theme_preference')" />
                </div>

                <div class="settings-group">
                    <div>
                        <h3 class="settings-group-title">Text size</h3>
                        <p class="field-note">Adjust the base reading size across dashboards, forms, tables, and navigation.</p>
                    </div>

                    <div class="settings-choice-grid settings-choice-grid--compact">
                        @foreach (['small' => 'Small', 'default' => 'Default', 'large' => 'Large'] as $value => $label)
                            <label class="settings-choice-card">
                                <input
                                    type="radio"
                                    name="font_size_preference"
                                    value="{{ $value }}"
                                    class="settings-choice-input"
                                    {{ old('font_size_preference', $user->font_size_preference ?? 'default') === $value ? 'checked' : '' }}
                                >
                                <span class="settings-choice-copy">
                                    <span class="settings-choice-title">{{ $label }}</span>
                                    <span class="settings-choice-note">Apply this text scale throughout the workspace.</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('font_size_preference')" />
                </div>

                <div class="auth-links">
                    <x-primary-button>Save appearance</x-primary-button>

                    @if (session('settings_status') === 'appearance-updated')
                        <p class="text-sm text-muted">Saved.</p>
                    @endif
                </div>
            </form>
        </article>

        <article class="settings-panel">
            <div class="panel-heading">
                <div class="panel-title-block">
                    <span class="page-kicker">Profile Picture</span>
                    <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">Avatar</h2>
                    <p class="panel-subtitle">Upload a profile image to replace your initials in the sidebar and account menu.</p>
                </div>
            </div>

            <div class="settings-avatar-shell mt-6">
                @if ($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="settings-avatar-preview">
                @else
                    <div class="settings-avatar-fallback" aria-hidden="true">
                        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a4 4 0 100-8 4 4 0 000 8zm-7 8a7 7 0 0114 0" />
                        </svg>
                    </div>
                @endif

                <div class="settings-avatar-copy">
                    <div class="settings-group-title">{{ $user->name }}</div>
                    <p class="field-note">PNG, JPG, WEBP, or GIF up to 2MB.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('settings.avatar.update') }}" enctype="multipart/form-data" class="auth-form mt-6">
                @csrf

                <div>
                    <x-input-label for="avatar" :value="__('Upload image')" />
                    <input id="avatar" name="avatar" type="file" accept="image/*" class="mt-1 block w-full">
                    <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                </div>

                <div class="auth-links">
                    <x-primary-button>{{ $avatarUrl ? 'Replace photo' : 'Upload photo' }}</x-primary-button>

                    @if (session('settings_status') === 'avatar-updated')
                        <p class="text-sm text-muted">Photo updated.</p>
                    @endif
                </div>
            </form>

            @if ($avatarUrl)
                <form method="POST" action="{{ route('settings.avatar.destroy') }}" class="mt-4">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn-secondary">Remove photo</button>

                    @if (session('settings_status') === 'avatar-removed')
                        <p class="mt-3 text-sm text-muted">Photo removed.</p>
                    @endif
                </form>
            @endif
        </article>
    </section>
</div>
@endsection
