<section>
    <div class="panel-heading">
        <div class="panel-title-block">
            <span class="page-kicker">Identity</span>
            <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">{{ __('Profile Information') }}</h2>
            <p class="panel-subtitle">{{ __("Update your account's profile information and email address.") }}</p>
        </div>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="auth-form mt-3">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="mt-2 text-sm text-muted">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="helper-link">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 auth-status">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div style="display:flex;justify-content:flex-end;align-items:center;gap:1rem;margin-top:1.5rem;">
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-muted">{{ __('Saved.') }}</p>
            @endif
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </div>
    </form>
</section>
