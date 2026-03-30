@extends('layouts.app')
@section('title', 'Edit Account')
@section('content')

<div class="max-w-lg mx-auto bg-white rounded-2xl shadow p-8">
    <h2 class="text-xl font-bold text-gray-800 mb-6">✏️ Edit Account</h2>

    <form method="POST" action="{{ route('accounts.update', $account) }}" class="space-y-5">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Account Name</label>
            <input type="text" name="name" value="{{ old('name', $account->name) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <select name="type"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="cash" {{ $account->type === 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="e-wallet" {{ $account->type === 'e-wallet' ? 'selected' : '' }}>E-Wallet (GCash, Maya)</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Balance (₱)</label>
            <input type="number" name="balance" value="{{ old('balance', $account->balance) }}" step="0.01"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description (optional)</label>
            <input type="text" name="description" value="{{ old('description', $account->description) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
        </div>

        <button type="submit"
                class="w-full bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-2 rounded-lg transition">
            Update Account
        </button>
    </form>
</div>

@endsection