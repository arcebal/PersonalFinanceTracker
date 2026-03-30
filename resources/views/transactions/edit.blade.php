@extends('layouts.app')
@section('title', 'Edit Transaction')
@section('content')

<div class="max-w-lg mx-auto bg-white rounded-2xl shadow p-8">
    <h2 class="text-xl font-bold text-gray-800 mb-6">✏️ Edit Transaction</h2>

    <form method="POST" action="{{ route('transactions.update', $transaction) }}" class="space-y-5">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <select name="type"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="income" {{ $transaction->type === 'income' ? 'selected' : '' }}>Income</option>
                <option value="expense" {{ $transaction->type === 'expense' ? 'selected' : '' }}>Expense</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Account</label>
            <select name="account_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                @foreach($accounts as $acc)
                <option value="{{ $acc->id }}" {{ $transaction->account_id == $acc->id ? 'selected' : '' }}>
                    {{ $acc->name }} (₱{{ number_format($acc->balance, 2) }})
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <select name="category_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ $transaction->category_id == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }} ({{ ucfirst($cat->type) }})
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Amount (₱)</label>
            <input type="number" name="amount" step="0.01" value="{{ $transaction->amount }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
            <input type="date" name="transaction_date" value="{{ $transaction->transaction_date }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description (optional)</label>
            <input type="text" name="description" value="{{ $transaction->description }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
        </div>

        <button type="submit"
                class="w-full bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-2 rounded-lg transition">
            Update Transaction
        </button>
    </form>
</div>

@endsection