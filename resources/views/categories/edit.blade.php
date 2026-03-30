@extends('layouts.app')
@section('title', 'Edit Category')
@section('content')

<div class="max-w-lg mx-auto bg-white rounded-2xl shadow p-8">
    <h2 class="text-xl font-bold text-gray-800 mb-6">✏️ Edit Category</h2>

    <form method="POST" action="{{ route('categories.update', $category) }}" class="space-y-5">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <select name="type"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="income" {{ $category->type === 'income' ? 'selected' : '' }}>Income</option>
                <option value="expense" {{ $category->type === 'expense' ? 'selected' : '' }}>Expense</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
            <input type="color" name="color" value="{{ $category->color }}"
                   class="w-16 h-10 border border-gray-300 rounded-lg cursor-pointer">
        </div>

        <button type="submit"
                class="w-full bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-2 rounded-lg transition">
            Update Category
        </button>
    </form>
</div>

@endsection