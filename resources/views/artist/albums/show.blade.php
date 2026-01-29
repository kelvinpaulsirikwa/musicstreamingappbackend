@extends('artist.layout')

@section('title', $album->title)

@section('header', 'Album Details')

@section('content')
@php
    $artist = App\Models\Artist::where('user_id', auth()->id())->first();
    
    if (!$artist) {
        return redirect()->route('artist.profile.create')
            ->with('error', 'Please create your artist profile first.');
    }
@endphp

<div class="max-w-4xl mx-auto">
    <!-- Album Header -->
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-6">
                @if ($album->cover_image)
                    <img src="{{ asset('uploads/albums/' . $album->cover_image) }}" alt="{{ $album->title }}" 
                         class="w-32 h-32 object-cover rounded-lg">
                @else
                    <div class="w-32 h-32 bg-gray-700 rounded-lg flex items-center justify-center">
                        <i class="fas fa-compact-disc text-5xl text-gray-600"></i>
                    </div>
                @endif
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">{{ $album->title }}</h1>
                    <p class="text-gray-400">Released: {{ \Carbon\Carbon::parse($album->release_date)->format('M d, Y') }}</p>
                    <p class="text-gray-400">Created: {{ $album->created_at->format('M d, Y') }}</p>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('albums.edit', $album) }}" 
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit Album
                </a>
                <form action="{{ route('albums.destroy', $album) }}" method="POST" class="inline-block"
                      onsubmit="return confirm('Are you sure you want to delete this album?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors">
                        <i class="fas fa-trash mr-2"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Songs in Album -->
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-white">Songs in this Album</h2>
            <a href="{{ route('songs.create') }}?album_id={{ $album->id }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>Add Song
            </a>
        </div>

        @php
            $songs = App\Models\Song::where('album_id', $album->id)
                ->with('categories')
                ->latest()
                ->get();
        @endphp

        @forelse($songs as $song)
            <div class="flex items-center justify-between p-4 bg-gray-700 rounded-lg mb-3 hover:bg-gray-600 transition-colors">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gray-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-music text-gray-400"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold">{{ $song->title }}</h3>
                        <div class="flex items-center space-x-4 text-sm text-gray-400">
                            <span>
                                @php
                                    $duration = $song->duration ?? 0;
                                    $minutes = floor($duration / 60);
                                    $seconds = $duration % 60;
                                    echo sprintf('%02d:%02d', $minutes, $seconds);
                                @endphp
                            </span>
                            <span>•</span>
                            <span>{{ $song->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex flex-wrap gap-1 mt-2">
                            @foreach($song->categories as $category)
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-600 text-gray-300">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ asset('uploads/songs/' . $song->audio_file) }}" 
                       class="inline-flex items-center justify-center w-10 h-10 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors" 
                       title="Play" target="_blank">
                        <i class="fas fa-play"></i>
                    </a>
                    <a href="{{ route('songs.edit', $song) }}" 
                       class="inline-flex items-center justify-center w-10 h-10 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('songs.destroy', $song) }}" method="POST" class="inline-block"
                          onsubmit="return confirm('Are you sure you want to delete this song?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center w-10 h-10 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <i class="fas fa-music text-4xl text-gray-600 mb-4"></i>
                <p class="text-gray-400 mb-4">No songs in this album yet</p>
                <a href="{{ route('songs.create') }}?album_id={{ $album->id }}" 
                   class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add First Song
                </a>
            </div>
        @endforelse
    </div>

    <!-- Actions -->
    <div class="mt-6 flex justify-between items-center">
        <a href="{{ route('albums.index') }}" 
           class="text-gray-400 hover:text-white transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Albums
        </a>
        <div class="text-sm text-gray-400">
            {{ $songs->count() }} song{{ $songs->count() != 1 ? 's' : '' }} in this album
        </div>
    </div>
</div>
@endsection
