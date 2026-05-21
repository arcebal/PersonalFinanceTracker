@extends('layouts.app')
@section('title', 'Add Category')
@section('content')

    <div class="page-shell">
        <section class="page-header">
            <div class="page-title-block">
                <h1 class="page-title">Add category</h1>
                <p class="page-subtitle">Create a reusable category for either income or expense transactions.</p>
            </div>
        </section>

        <section class="form-layout">
            <div class="form-panel">
                <form method="POST" action="{{ route('categories.store') }}" class="auth-form">
                    @csrf

                    <div class="panel-heading mb-6">
                        <div class="panel-title-block">
                            <span class="page-kicker">New category</span>
                            <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">Category details</h2>
                            <p class="panel-subtitle">A strong category structure makes charts and budget targets easier to
                                trust.</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-field">
                            <label class="field-label">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Food & dining">
                            @error('name')
                                <p class="text-sm text-expense">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-field md:col-span-6">
                            <label class="field-label">Type</label>
                            <select name="type">
                                <option value="income">Income</option>
                                <option value="expense">Expense</option>
                            </select>
                        </div>

                        <div class="form-field md:col-span-6">
                            <label class="field-label">Color</label>
                            <input type="color" name="color" value="#138AF2" class="field-color">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <a href="{{ route('categories.index') }}" class="btn-secondary">Cancel</a>
                        <button type="submit" class="btn-primary">Save category</button>
                    </div>
                </form>
            </div>
        </section>
    </div>

@endsection
