<section>
    <div class="panel-heading">
        <div class="panel-title-block">
            <span class="page-kicker">Security</span>
            <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">{{ __('Update Password') }}</h2>
            <p class="panel-subtitle">{{ __('Ensure your account is using a long, random password to stay secure.') }}
            </p>
        </div>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="auth-form mt-3" style="gap:0.75rem;">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password"
                class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div style="display:flex;justify-content:flex-end;align-items:center;gap:1rem;margin-top:1.5rem;">
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-muted">{{ __('Saved.') }}</p>
            @endif
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </div>
    </form>
</section>
