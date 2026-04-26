@extends('layouts.app')
@section('title','Deleted Transactions')
@section('content')

<div class="page-shell">
    <section class="page-header">
        <div class="page-title-block">
            <span class="page-kicker">Transactions</span>
            <h1 class="page-title">Deleted transactions</h1>
            <p class="page-subtitle">Restore removed entries or permanently delete them from your history.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('transactions.index') }}" class="btn-secondary">Back to transactions</a>
        </div>
    </section>

    <section class="table-shell">
        @if ($transactions->count())
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Account</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $t)
                            <tr>
                                <td>{{ \Illuminate\Support\Carbon::parse($t->transaction_date)->format('M d, Y') }}</td>
                                <td class="table-title">{{ $t->description ?: 'Recorded transaction' }}</td>
                                <td>{{ $t->category->name ?? '—' }}</td>
                                <td>{{ $t->account->name ?? '—' }}</td>
                                <td>{{ ucfirst($t->type) }}</td>
                                <td>₱{{ number_format($t->amount, 2) }}</td>
                                <td>
                                    <div class="table-actions">
                                        <form action="{{ route('transactions.restore', $t->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-secondary">Restore</button>
                                        </form>
                                        <form action="{{ route('transactions.forceDelete', $t->id) }}" method="POST">
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
                <p>No deleted transactions.</p>
            </div>
        @endif
    </section>
</div>

@endsection
