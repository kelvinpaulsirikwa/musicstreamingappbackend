@extends('artist.layout')

@section('title', $song->title)

@section('header', 'Song Details')

@section('content')
@php
    $artist = App\Models\Artist::where('user_id', auth()->id())->first();
    
    if (!$artist) {
        return redirect()->route('artist.profile.create')
            ->with('error', 'Please create your artist profile first.');
    }
@endphp

<div class="max-w-4xl mx-auto">
    <!-- Song Header -->
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-6">
                <div class="w-24 h-24 bg-gray-700 rounded-lg flex items-center justify-center">
                    <i class="fas fa-music text-4xl text-gray-600"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">{{ $song->title }}</h1>
                    <p class="text-gray-400">Album: {{ $song->album->title ?? 'No Album' }}</p>
                    <p class="text-gray-400">Duration: 
                        @php
                            $duration = $song->duration ?? 0;
                            $minutes = floor($duration / 60);
                            $seconds = $duration % 60;
                            echo sprintf('%02d:%02d', $minutes, $seconds);
                        @endphp
                    </p>
                    <p class="text-gray-400">Uploaded: {{ $song->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ asset('uploads/songs/' . $song->audio_file) }}" 
                   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors" 
                   target="_blank">
                    <i class="fas fa-play mr-2"></i>Play
                </a>
                <a href="{{ route('songs.edit', $song) }}" 
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <form action="{{ route('songs.destroy', $song) }}" method="POST" class="inline-block"
                      onsubmit="return confirm('Are you sure you want to delete this song?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors">
                        <i class="fas fa-trash mr-2"></i>Delete
                    </button>
                </form>
            </div>
        </div>

        <!-- Categories -->
        @if($song->categories->isNotEmpty())
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-white mb-2">Categories</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($song->categories as $category)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-700 text-gray-300">
                            {{ $category->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Song Information -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
            <h3 class="text-lg font-semibold text-white mb-4">Basic Information</h3>
            
            <div class="space-y-3">
                <div>
                    <p class="text-gray-400 text-sm">Song Title</p>
                    <p class="text-white">{{ $song->title }}</p>
                </div>
                
                <div>
                    <p class="text-gray-400 text-sm">Audio File</p>
                    <p class="text-white text-sm">{{ $song->audio_file }}</p>
                </div>
                
                <div>
                    <p class="text-gray-400 text-sm">Duration</p>
                    <p class="text-white">
                        @php
                            $duration = $song->duration ?? 0;
                            $minutes = floor($duration / 60);
                            $seconds = $duration % 60;
                            echo sprintf('%02d:%02d', $minutes, $seconds);
                        @endphp
                    </p>
                </div>
            </div>
        </div>

        <!-- System Information -->
        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
            <h3 class="text-lg font-semibold text-white mb-4">System Information</h3>
            
            <div class="space-y-3">
                <div>
                    <p class="text-gray-400 text-sm">Song ID</p>
                    <p class="text-white">#{{ $song->id }}</p>
                </div>
                
                <div>
                    <p class="text-gray-400 text-sm">Album</p>
                    <p class="text-white">{{ $song->album->title ?? 'No Album' }}</p>
                </div>
                
                <div>
                    <p class="text-gray-400 text-sm">Created At</p>
                    <p class="text-white">{{ $song->created_at->format('M d, Y H:i:s') }}</p>
                </div>
                
                <div>
                    <p class="text-gray-400 text-sm">Last Updated</p>
                    <p class="text-white">{{ $song->updated_at->format('M d, Y H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-8 pt-6 border-t border-gray-700 flex justify-between items-center">
        <a href="{{ route('songs.index') }}" 
           class="text-gray-400 hover:text-white transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Songs
        </a>
        
        @if($song->album_id)
            <a href="{{ route('albums.show', $song->album) }}" 
               class="text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-compact-disc mr-2"></i>View Album
            </a>
        @endif
    </div>
</div>
@endsection
