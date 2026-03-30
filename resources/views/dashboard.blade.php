@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<h2 class="text-2xl font-bold text-gray-800 mb-6">📊 Dashboard</h2>

{{-- Summary Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-blue-600 text-white rounded-2xl shadow p-6">
        <p class="text-sm font-medium opacity-80">Total Balance</p>
        <h3 class="text-3xl font-bold mt-1">₱{{ number_format($totalBalance, 2) }}</h3>
    </div>
    <div class="bg-emerald-500 text-white rounded-2xl shadow p-6">
        <p class="text-sm font-medium opacity-80">Total Income</p>
        <h3 class="text-3xl font-bold mt-1">₱{{ number_format($totalIncome, 2) }}</h3>
    </div>
    <div class="bg-red-500 text-white rounded-2xl shadow p-6">
        <p class="text-sm font-medium opacity-80">Total Expenses</p>
        <h3 class="text-3xl font-bold mt-1">₱{{ number_format($totalExpense, 2) }}</h3>
    </div>
</div>

{{-- Recent Transactions --}}
<div class="bg-white rounded-2xl shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h4 class="text-lg font-semibold text-gray-700">Recent Transactions</h4>
    </div>
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
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentTrans as $t)
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
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">No transactions yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection