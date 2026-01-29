@extends('admin.layout')

@section('title', 'Categories Management')

@section('header', 'Categories Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-white">Categories Management</h1>
        <p class="text-gray-400">Manage music categories</p>
    </div>
    <a href="{{ route('categories.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center space-x-2 transition-colors">
        <i class="fas fa-plus"></i>
        <span>Add Category</span>
    </a>
</div>

<!-- Search -->
<div class="bg-gray-800 p-6 rounded-lg border border-gray-700 mb-6">
    <form method="GET" action="{{ route('categories.index') }}">
        <div class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search categories..." 
                   class="flex-1 px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-500">
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                <i class="fas fa-search mr-2"></i>Search
            </button>
        </div>
    </form>
</div>

<!-- Categories Table -->
<div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-700">
                    <th class="text-left py-3 px-4 text-gray-300 font-semibold">Name</th>
                    <th class="text-left py-3 px-4 text-gray-300 font-semibold">Description</th>
                    <th class="text-left py-3 px-4 text-gray-300 font-semibold">Created</th>
                    <th class="text-center py-3 px-4 text-gray-300 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr class="border-b border-gray-700 hover:bg-gray-700 transition-colors">
                        <td class="py-3 px-4">
                            <span class="text-white font-medium">{{ $category->name }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-gray-300">{{ $category->description ?? 'No description' }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-gray-400 text-sm">{{ $category->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center justify-center space-x-3">
                                <a href="{{ route('categories.show', $category) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded transition-colors" title="View">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                <a href="{{ route('categories.edit', $category) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 bg-yellow-600 hover:bg-yellow-700 text-white rounded transition-colors" title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded transition-colors" title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-8">
                            <div class="text-gray-400">
                                <i class="fas fa-tags text-4xl mb-4"></i>
                                <p>No categories found.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($categories->hasPages())
        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection
