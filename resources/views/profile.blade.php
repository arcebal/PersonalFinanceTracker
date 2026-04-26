@extends('layouts.app')

@section('title','Profile')

@section('content')
<div class="page-shell">
    <section class="section-card">
        <div class="page-title-block">
            <span class="page-kicker">Profile</span>
            <h1 class="page-title">Profile overview</h1>
            <p class="page-subtitle">Use the full profile settings screen to update account details, password, and security preferences.</p>
        </div>
        <div class="page-actions mt-6">
            <a href="{{ route('profile.edit') }}" class="btn-primary">Open profile settings</a>
        </div>
    </section>
</div>
@endsection
