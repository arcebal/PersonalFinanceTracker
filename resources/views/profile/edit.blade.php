@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="page-shell">
        <section class="page-header">
            <div class="page-title-block">
                <h1 class="page-title">Account settings</h1>
                <p class="page-subtitle">Manage identity, password, and account-level security controls.</p>
            </div>
        </section>

        <section class="profile-grid">
            <div class="col-span-12 lg:col-span-7 flex flex-col gap-6">
                <div class="profile-panel">
                    @include('profile.partials.update-profile-information-form')
                </div>
                <div class="profile-panel">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="profile-panel col-span-12 lg:col-span-5">
                @php $avatarUrl = auth()->user()->avatarUrl(); @endphp
                <div class="panel-heading">
                    <div class="panel-title-block">
                        <span class="page-kicker">Profile Picture</span>
                        <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">Avatar</h2>
                        <p class="panel-subtitle">Upload a profile image to replace your initials in the sidebar and account
                            menu.</p>
                    </div>
                </div>

                <div class="settings-avatar-shell mt-3">
                    @if ($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ auth()->user()->name }}" class="settings-avatar-preview">
                    @else
                        <div class="settings-avatar-fallback" aria-hidden="true">
                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 12a4 4 0 100-8 4 4 0 000 8zm-7 8a7 7 0 0114 0" />
                            </svg>
                        </div>
                    @endif
                    <div class="settings-avatar-copy">
                        <div class="settings-group-title">{{ auth()->user()->name }}</div>
                        <p class="field-note">PNG, JPG, WEBP, or GIF up to 2MB.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('settings.avatar.update') }}" enctype="multipart/form-data"
                    class="auth-form mt-3" style="gap:0.75rem;">
                    @csrf
                    <div>
                        <x-input-label for="avatar" :value="__('Upload image')" />
                        <input id="avatar" name="avatar" type="file" accept="image/*"
                            class="mt-1 block w-full text-sm text-[var(--text-secondary)]
                            file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0
                            file:text-sm file:font-medium file:cursor-pointer cursor-pointer
                            file:bg-[var(--bg-panel-soft)] file:text-[var(--text-primary)]
                            hover:file:bg-[var(--bg-panel-strong)]">
                        <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                    </div>
                    <div style="display:flex;justify-content:flex-end;align-items:center;gap:1rem;">
                        <x-primary-button>{{ $avatarUrl ? 'Replace photo' : 'Upload photo' }}</x-primary-button>
                        @if (session('status') === 'avatar-updated')
                            <p class="text-sm text-muted">Photo updated.</p>
                        @endif
                    </div>
                </form>

                @if ($avatarUrl)
                    <form method="POST" action="{{ route('settings.avatar.destroy') }}" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-secondary">Remove photo</button>
                        @if (session('status') === 'avatar-removed')
                            <p class="mt-3 text-sm text-muted">Photo removed.</p>
                        @endif
                    </form>
                @endif
            </div>

            <div class="profile-panel danger col-span-12" style="padding-top:1.25rem;padding-bottom:1.25rem;">
                @include('profile.partials.delete-user-form')
            </div>
        </section>
    </div>
@endsection
