@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="page-shell">
        <section class="page-header">
            <div class="page-title-block">
                <span class="page-kicker">Profile</span>
                <h1 class="page-title">Account settings</h1>
                <p class="page-subtitle">Manage identity, password, and account-level security controls.</p>
            </div>
        </section>

        <section class="profile-grid">
            <div class="profile-panel col-span-12 lg:col-span-6">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="profile-panel col-span-12 lg:col-span-6">
                @include('profile.partials.update-password-form')
            </div>

            <div class="profile-panel danger col-span-12">
                @include('profile.partials.delete-user-form')
            </div>
        </section>
    </div>
@endsection
