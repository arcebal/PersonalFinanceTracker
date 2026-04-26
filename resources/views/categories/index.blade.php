@extends('layouts.app')
@section('title', 'Categories')
@section('content')

<div class="page-shell">
    <section class="page-header">
        <div class="page-title-block">
            <span class="page-kicker">Categories</span>
            <h1 class="page-title">Classification rules</h1>
            <p class="page-subtitle">Use categories to keep reports readable and budgets accurate across income and expense entries.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('categories.trashed') }}" class="btn-secondary">View trash</a>
            <a href="{{ route('categories.create') }}" class="btn-primary">Add category</a>
        </div>
    </section>

    <section class="table-shell">
        @if ($categories->count())
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Color</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $cat)
                            <tr>
                                <td>
                                    <div class="table-title">{{ $cat->name }}</div>
                                    <div class="text-sm text-muted">Used to group related transactions.</div>
                                </td>
                                <td>
                                    <span class="{{ $cat->type === 'income' ? 'badge-income' : 'badge-expense' }}">
                                        {{ ucfirst($cat->type) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="table-badge" style="border-color: {{ $cat->color }}33; background-color: {{ $cat->color }}1f; color: {{ $cat->color }};">
                                        <span class="inline-block h-3 w-3 rounded-full" style="background-color: {{ $cat->color }}"></span>
                                        {{ $cat->color }}
                                    </span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('categories.edit', $cat) }}" class="btn-secondary">Edit</a>
                                        <form action="{{ route('categories.destroy', $cat) }}" method="POST" class="swal-delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="delete-btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h4l2 2h4v8H7V7zm-2 0h2v10a2 2 0 002 2h8" />
                    </svg>
                </div>
                <p>No categories yet. Add income and expense categories to organize transactions and budgets.</p>
                <a href="{{ route('categories.create') }}" class="btn-primary">Create category</a>
            </div>
        @endif
    </section>
</div>

@endsection
