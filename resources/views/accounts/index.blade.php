@extends('layouts.app')
@section('title', 'Accounts')
@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">🏦 Accounts</h2>
    <a href="{{ route('accounts.create') }}"
       class="bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
        + Add Account
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @forelse($accounts as $acc)
    <div class="bg-white rounded-2xl shadow p-6 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-bold text-gray-800">{{ $acc->name }}</h3>
                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full font-medium">
                    {{ ucfirst($acc->type) }}
                </span>
            </div>
            <p class="text-gray-400 text-sm mb-4">{{ $acc->description ?? 'No description' }}</p>
            <p class="text-2xl font-bold text-blue-600">₱{{ number_format($acc->balance, 2) }}</p>
        </div>
        <div class="flex gap-2 mt-6">
            <a href="{{ route('accounts.edit', $acc) }}"
               class="flex-1 text-center bg-yellow-400 hover:bg-yellow-500 text-white text-sm font-semibold py-2 rounded-lg transition">
                Edit
            </a>
            <form action="{{ route('accounts.destroy', $acc) }}" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <button onclick="return confirm('Delete this account?')"
                        class="w-full bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2 rounded-lg transition">
                    Delete
                </button>
            </form>
        </div>
    </div>
    @empty
    <p class="text-gray-400 col-span-3">No accounts yet.</p>
    @endforelse
</div>

@endsection