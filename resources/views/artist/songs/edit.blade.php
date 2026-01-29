@extends('artist.layout')

@section('title', 'Edit Song')

@section('header', 'Edit Song')

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
        <h2 class="text-2xl font-bold text-white mb-6">Edit Song</h2>
        
        <form action="{{ route('songs.update', $song) }}" method="POST" enctype="multipart/form-data">
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
                <!-- Song Title -->
                <div>
                    <label for="title" class="block text-gray-300 text-sm font-medium mb-2">
                        Song Title <span class="text-red-400">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title', $song->title) }}" required
                           class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-500">
                </div>

                <!-- Album Selection -->
                <div>
                    <label for="album_id" class="block text-gray-300 text-sm font-medium mb-2">
                        Album
                    </label>
                    <select id="album_id" name="album_id" 
                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
                        <option value="">No Album</option>
                        @foreach($albums as $albumId => $albumTitle)
                            <option value="{{ $albumId }}" {{ old('album_id', $song->album_id) == $albumId ? 'selected' : '' }}>{{ $albumTitle }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Categories -->
                <div>
                    <label class="block text-gray-300 text-sm font-medium mb-2">
                        Categories
                    </label>
                    <div class="space-y-2 max-h-32 overflow-y-auto bg-gray-700 p-3 rounded-lg">
                        @foreach($categories as $categoryId => $categoryName)
                            <label class="flex items-center text-gray-300 hover:text-white cursor-pointer">
                                <input type="checkbox" name="categories[]" value="{{ $categoryId }}" 
                                       {{ in_array($categoryId, old('categories', $song->categories->pluck('id')->toArray())) ? 'checked' : '' }}
                                       class="mr-2 bg-gray-600 border-gray-500 rounded text-purple-600 focus:ring-purple-500">
                                <span class="text-sm">{{ $categoryName }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-gray-500 text-xs mt-1">Select all that apply</p>
                </div>

                <!-- Audio File -->
                <div>
                    <label for="audio_file" class="block text-gray-300 text-sm font-medium mb-2">
                        Audio File
                    </label>
                    <div class="bg-gray-700 p-3 rounded-lg">
                        <p class="text-gray-300 text-sm mb-2">Current file: {{ $song->audio_file }}</p>
                        <input type="file" id="audio_file" name="audio_file" accept="audio/*"
                               class="w-full px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-purple-600 file:text-white hover:file:bg-purple-700">
                        <p class="text-gray-500 text-xs mt-1">Leave empty to keep current file (Max: 10MB, MP3/WAV/OGG/M4A)</p>
                    </div>
                </div>

                <!-- Song Info -->
                <div class="mt-6 p-4 bg-gray-700 rounded-lg">
                    <p class="text-gray-400 text-sm">
                        <i class="fas fa-info-circle mr-1"></i>
                        Song ID: {{ $song->id }} | Duration: 
                        @php
                            $duration = $song->duration ?? 0;
                            $minutes = floor($duration / 60);
                            $seconds = $duration % 60;
                            echo sprintf('%02d:%02d', $minutes, $seconds);
                        @endphp
                        | Created: {{ $song->created_at->format('M d, Y H:i') }}
                    </p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('songs.show', $song) }}" 
                   class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>Update Song
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
