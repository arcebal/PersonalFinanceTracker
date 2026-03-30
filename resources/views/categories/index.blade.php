@extends('layouts.app')
@section('title', 'Categories')
@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">🗂️ Categories</h2>
    <a href="{{ route('categories.create') }}"
       class="bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
        + Add Category
    </a>
</div>

<div class="bg-white rounded-2xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-6 py-3 text-left">Name</th>
                <th class="px-6 py-3 text-left">Type</th>
                <th class="px-6 py-3 text-left">Color</th>
                <th class="px-6 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($categories as $cat)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-medium text-gray-800">{{ $cat->name }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        {{ $cat->type === 'income' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                        {{ ucfirst($cat->type) }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full inline-block border"
                              style="background-color: {{ $cat->color }}"></span>
                        <span class="text-gray-500 text-xs">{{ $cat->color }}</span>
                    </span>
                </td>
                <td class="px-6 py-4 flex gap-2">
                    <a href="{{ route('categories.edit', $cat) }}"
                       class="bg-yellow-400 hover:bg-yellow-500 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                        Edit
                    </a>
                    <form action="{{ route('categories.destroy', $cat) }}" method="POST">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Delete this category?')"
                                class="bg-red-500 hover:bg-red-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-8 text-center text-gray-400">No categories yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection