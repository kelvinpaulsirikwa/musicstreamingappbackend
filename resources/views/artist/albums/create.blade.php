@extends('artist.layout')

@section('title', 'Create Album')

@section('header', 'Create New Album')

@section('content')
@php
    $artist = App\Models\Artist::where('user_id', auth()->id())->first();
    
    if (!$artist) {
        return redirect()->route('artist.profile.create')
            ->with('error', 'Please create your artist profile first.');
    }
@endphp

<div class="max-w-2xl mx-auto">
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
        <h2 class="text-2xl font-bold text-white mb-6">Create New Album</h2>
        
        <form action="{{ route('albums.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-900 border border-red-700 rounded-lg">
                    @foreach ($errors->all() as $error)
                        <p class="text-red-300 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="space-y-6">
                <!-- Album Title -->
                <div>
                    <label for="title" class="block text-gray-300 text-sm font-medium mb-2">
                        Album Title <span class="text-red-400">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                           class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-500"
                           placeholder="Enter album title">
                </div>

                <!-- Release Date -->
                <div>
                    <label for="release_date" class="block text-gray-300 text-sm font-medium mb-2">
                        Release Date <span class="text-red-400">*</span>
                    </label>
                    <input type="date" id="release_date" name="release_date" value="{{ old('release_date') }}" required
                           class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
                </div>

                <!-- Cover Image -->
                <div>
                    <label for="cover_image" class="block text-gray-300 text-sm font-medium mb-2">
                        Cover Image
                    </label>
                    <div class="flex items-center space-x-4">
                        <div class="w-20 h-20 bg-gray-700 rounded-lg flex items-center justify-center">
                            <i class="fas fa-image text-gray-500 text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <input type="file" id="cover_image" name="cover_image" accept="image/*"
                                   class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                            <p class="text-gray-500 text-xs mt-1">Optional: Upload album cover (Max: 2MB, JPG/PNG)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('albums.index') }}" 
                   class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Create Album
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
