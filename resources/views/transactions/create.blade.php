@extends('layouts.app')
@section('title', 'Add Transaction')
@section('content')

<div class="max-w-lg mx-auto bg-white rounded-2xl shadow p-8">
    <h2 class="text-xl font-bold text-gray-800 mb-6">➕ Add Transaction</h2>

    <form method="POST" action="{{ route('transactions.store') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <select name="type"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="income">Income</option>
                <option value="expense">Expense</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Account</label>
            <select name="account_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                @foreach($accounts as $acc)
                <option value="{{ $acc->id }}">{{ $acc->name }} (₱{{ number_format($acc->balance, 2) }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <select name="category_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }} ({{ ucfirst($cat->type) }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Amount (₱)</label>
            <input type="number" name="amount" step="0.01" min="0.01"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
            @error('amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
            <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description (optional)</label>
            <input type="text" name="description"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
        </div>

        <button type="submit"
                class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-2 rounded-lg transition">
            Save Transaction
        </button>
    </form>
</div>

@endsection