@extends('layouts.app')
@section('title', 'Add Account')
@section('content')

<div class="max-w-lg mx-auto bg-white rounded-2xl shadow p-8">
    <h2 class="text-xl font-bold text-gray-800 mb-6">➕ Add Account</h2>

    <form method="POST" action="{{ route('accounts.store') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Account Name</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <select name="type"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="cash">Cash</option>
                <option value="e-wallet">E-Wallet (GCash, Maya)</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Initial Balance (₱)</label>
            <input type="number" name="balance" value="{{ old('balance', 0) }}" step="0.01" min="0"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
            @error('balance')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description (optional)</label>
            <input type="text" name="description" value="{{ old('description') }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
        </div>

        <button type="submit"
                class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-2 rounded-lg transition">
            Save Account
        </button>
    </form>
</div>

@endsection