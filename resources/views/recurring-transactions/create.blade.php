@extends('layouts.app')
@section('title', 'Add Recurring Item')
@section('content')

    <div class="page-shell">
        <section class="page-header">
            <div class="page-title-block">
                <h1 class="page-title">Create a recurring plan</h1>
                <p class="page-subtitle">Track bills, salaries, and subscriptions with reminder-driven schedules instead of
                    one-off manual entries every time.</p>
            </div>

        </section>

        <section class="form-layout">
            <div class="form-panel">
                @include('recurring-transactions._form', [
                    'action' => route('recurring-transactions.store'),
                    'submitLabel' => 'Save recurring item',
                ])
            </div>
        </section>
    </div>

@endsection
