@extends('layouts.app')
@section('title','Deleted Categories')
@section('content')

<div class="page-shell">
    <section class="page-header">
        <div class="page-title-block">
            <span class="page-kicker">Categories</span>
            <h1 class="page-title">Deleted categories</h1>
            <p class="page-subtitle">Restore archived categories or remove them permanently if they are no longer needed.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('categories.index') }}" class="btn-secondary">Back to categories</a>
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
                        @foreach($categories as $c)
                            <tr>
                                <td class="table-title">{{ $c->name }}</td>
                                <td>{{ ucfirst($c->type) }}</td>
                                <td>
                                    <span class="table-badge" style="border-color: {{ $c->color }}33; background-color: {{ $c->color }}1f; color: {{ $c->color }};">
                                        <span class="inline-block h-3 w-3 rounded-full" style="background-color: {{ $c->color }}"></span>
                                        {{ $c->color }}
                                    </span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <form action="{{ route('categories.restore', $c->id) }}" method="POST">
                                            @csrf
                                            <button class="btn-secondary">Restore</button>
                                        </form>
                                        <form action="{{ route('categories.forceDelete', $c->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Permanently delete?')" class="btn-danger">Delete permanently</button>
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12m-9 4v6m6-6v6M8 7l1-2h6l1 2m-9 0v11a2 2 0 002 2h6a2 2 0 002-2V7" />
                    </svg>
                </div>
                <p>No deleted categories.</p>
            </div>
        @endif
    </section>
</div>

@endsection
