@extends('admin.layout')

@section('title', 'Category Details')

@section('header', 'Category Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
        <!-- Category Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-white">{{ $category->name }}</h2>
                <p class="text-gray-400">Category Information</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('categories.edit', $category) }}" 
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block"
                      onsubmit="return confirm('Are you sure you want to delete this category?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors">
                        <i class="fas fa-trash mr-2"></i>Delete
                    </button>
                </form>
            </div>
        </div>

        <!-- Category Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-white mb-4">Basic Information</h3>
                
                <div>
                    <p class="text-gray-400 text-sm">Category Name</p>
                    <p class="text-white">{{ $category->name }}</p>
                </div>
                
                <div>
                    <p class="text-gray-400 text-sm">Description</p>
                    <p class="text-white">{{ $category->description ?? 'No description provided' }}</p>
                </div>
            </div>

            <!-- System Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-white mb-4">System Information</h3>
                
                <div>
                    <p class="text-gray-400 text-sm">Category ID</p>
                    <p class="text-white">#{{ $category->id }}</p>
                </div>
                
                <div>
                    <p class="text-gray-400 text-sm">Created At</p>
                    <p class="text-white">{{ $category->created_at->format('M d, Y H:i:s') }}</p>
                </div>
                
                <div>
                    <p class="text-gray-400 text-sm">Last Updated</p>
                    <p class="text-white">{{ $category->updated_at->format('M d, Y H:i:s') }}</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 pt-6 border-t border-gray-700 flex justify-between items-center">
            <a href="{{ route('categories.index') }}" 
               class="text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Categories
            </a>
        </div>
    </div>
</div>
@endsection
