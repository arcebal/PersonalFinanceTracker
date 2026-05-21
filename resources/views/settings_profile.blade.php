@extends('layouts.app')

@section('title', 'Settings')

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
                    <h1 class="page-title">Workspace settings</h1>
                    <p class="page-subtitle">Manage display preferences across the app.</p>
                </div>
            </div>
        </section>

        <section class="settings-grid" style="display:flex;justify-content:center;">
            <article class="settings-panel" style="width:100%;max-width:700px;">
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
                                    <input type="radio" name="theme_preference" value="{{ $value }}"
                                        class="settings-choice-input"
                                        {{ old('theme_preference', $selectedThemePreference) === $value ? 'checked' : '' }}>
                                    <span class="settings-choice-copy">
                                        <span class="settings-theme-preview {{ $themeOption['preview'] }}"
                                            aria-hidden="true">
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
                            <p class="field-note">Adjust the base reading size across dashboards, forms, tables, and
                                navigation.</p>
                        </div>

                        <div class="settings-choice-grid settings-choice-grid--compact">
                            @foreach (['small' => 'Small', 'default' => 'Default', 'large' => 'Large'] as $value => $label)
                                <label class="settings-choice-card">
                                    <input type="radio" name="font_size_preference" value="{{ $value }}"
                                        class="settings-choice-input"
                                        {{ old('font_size_preference', $user->font_size_preference ?? 'default') === $value ? 'checked' : '' }}>
                                    <span class="settings-choice-copy">
                                        <span class="settings-choice-title">{{ $label }}</span>
                                        <span class="settings-choice-note">Apply this text scale throughout the
                                            workspace.</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('font_size_preference')" />
                    </div>

                    <div class="auth-links" style="display:flex;justify-content:flex-end;align-items:center;gap:1rem;">
                        <x-primary-button>Save appearance</x-primary-button>

                        @if (session('settings_status') === 'appearance-updated')
                            <p class="text-sm text-muted">Saved.</p>
                        @endif
                    </div>
                </form>
            </article>
        </section>
    </div>
@endsection
