@extends('layouts.app')
@section('title', 'Edit Recurring Item')
@section('content')

<div class="page-shell">
    <section class="page-header">
        <div class="page-title-block">
            <span class="page-kicker">Recurring</span>
            <h1 class="page-title">Edit recurring plan</h1>
            <p class="page-subtitle">Adjust due dates, reminder timing, and schedule details without losing the history of confirmed transactions.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('recurring-transactions.index') }}" class="btn-secondary">Back to recurring items</a>
        </div>
    </section>

    <section class="form-layout">
        <div class="form-panel">
            @include('recurring-transactions._form', [
                'action' => route('recurring-transactions.update', $recurringTransaction),
                'submitLabel' => 'Update recurring item',
                'recurringTransaction' => $recurringTransaction,
            ])
        </div>
    </section>
</div>

@endsection
