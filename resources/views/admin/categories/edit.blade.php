@extends('admin.layout')

@section('title', 'Edit Category')

@section('header', 'Edit Category')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
        <h2 class="text-2xl font-bold text-white mb-6">Edit Category</h2>
        
        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-900 border border-red-700 rounded-lg">
                    @foreach ($errors->all() as $error)
                        <p class="text-red-300 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-gray-300 text-sm font-medium mb-2">
                        Category Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required
                           class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-500">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-gray-300 text-sm font-medium mb-2">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-500">{{ old('description', $category->description) }}</textarea>
                </div>

                <!-- Category Info -->
                <div class="mt-6 p-4 bg-gray-700 rounded-lg">
                    <p class="text-gray-400 text-sm">
                        <i class="fas fa-info-circle mr-1"></i>
                        Category ID: {{ $category->id }} | Created: {{ $category->created_at->format('M d, Y H:i') }}
                    </p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('categories.index') }}" 
                   class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>Update Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
