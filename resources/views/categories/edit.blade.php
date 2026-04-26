@extends('layouts.app')
@section('title', 'Edit Category')
@section('content')

<div class="page-shell">
    <section class="page-header">
        <div class="page-title-block">
            <span class="page-kicker">Categories</span>
            <h1 class="page-title">Edit category</h1>
            <p class="page-subtitle">Adjust the label, type, or color used across your reports and budgets.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('categories.index') }}" class="btn-secondary">Back to categories</a>
        </div>
    </section>

    <section class="form-layout">
        <div class="form-panel">
            <form method="POST" action="{{ route('categories.update', $category) }}" class="auth-form">
                @csrf
                @method('PUT')

                <div class="panel-heading mb-6">
                    <div class="panel-title-block">
                        <span class="page-kicker">Update category</span>
                        <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">{{ $category->name }}</h2>
                        <p class="panel-subtitle">Changes here will immediately affect the way transactions are grouped.</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-field">
                        <label class="field-label">Name</label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}">
                    </div>

                    <div class="form-field md:col-span-6">
                        <label class="field-label">Type</label>
                        <select name="type">
                            <option value="income" {{ $category->type === 'income' ? 'selected' : '' }}>Income</option>
                            <option value="expense" {{ $category->type === 'expense' ? 'selected' : '' }}>Expense</option>
                        </select>
                    </div>

                    <div class="form-field md:col-span-6">
                        <label class="field-label">Color</label>
                        <input type="color" name="color" value="{{ $category->color }}" class="field-color">
                    </div>
                </div>

                <div class="page-actions mt-6">
                    <button type="submit" class="btn-primary">Update category</button>
                    <a href="{{ route('categories.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </section>
</div>

@endsection
