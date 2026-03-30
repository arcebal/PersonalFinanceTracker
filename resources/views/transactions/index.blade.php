@extends('layouts.app')
@section('title', 'Transactions')
@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">💸 Transactions</h2>
    <a href="{{ route('transactions.create') }}"
       class="bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
        + Add Transaction
    </a>
</div>

<div class="bg-white rounded-2xl shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">Date</th>
                    <th class="px-6 py-3 text-left">Description</th>
                    <th class="px-6 py-3 text-left">Category</th>
                    <th class="px-6 py-3 text-left">Account</th>
                    <th class="px-6 py-3 text-left">Type</th>
                    <th class="px-6 py-3 text-left">Amount</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transactions as $t)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-600">{{ $t->transaction_date }}</td>
                    <td class="px-6 py-4 text-gray-800">{{ $t->description ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-white text-xs font-semibold"
                              style="background-color: {{ $t->category->color }}">
                            {{ $t->category->name }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $t->account->name }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $t->type === 'income' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($t->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold {{ $t->type === 'income' ? 'text-emerald-600' : 'text-red-500' }}">
                        {{ $t->type === 'income' ? '+' : '-' }}₱{{ number_format($t->amount, 2) }}
                    </td>
                    <td class="px-6 py-4 flex gap-2">
                        <a href="{{ route('transactions.edit', $t) }}"
                           class="bg-yellow-400 hover:bg-yellow-500 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                            Edit
                        </a>
                        <form action="{{ route('transactions.destroy', $t) }}" method="POST">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Delete this transaction?')"
                                    class="bg-red-500 hover:bg-red-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-400">No transactions yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection