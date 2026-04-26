@extends('layouts.auth')

@section('title','Login')
@section('subtitle','Sign in to review balances, budgets, and recent activity.')

@section('content')
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center gap-3 text-sm text-muted">
                <input id="remember_me" type="checkbox" class="rounded" name="remember">
                <span>{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="auth-links mt-2">
            @if (Route::has('password.request'))
                <a class="helper-link" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <div class="text-sm text-muted">
            {{ __("Don't have an account?") }}
            <a class="helper-link" href="{{ route('register') }}">{{ __('Create one') }}</a>
        </div>
    </form>
@endsection
