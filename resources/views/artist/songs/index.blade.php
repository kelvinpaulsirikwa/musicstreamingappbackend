@extends('artist.layout')

@section('title', 'My Songs')

@section('header', 'My Songs')

@section('content')
@php
    $artist = App\Models\Artist::where('user_id', auth()->id())->first();
    
    if (!$artist) {
        return redirect()->route('artist.profile.create')
            ->with('error', 'Please create your artist profile first.');
    }
@endphp

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-white">My Songs</h1>
        <p class="text-gray-400">Manage your music catalog</p>
    </div>
    <a href="{{ route('songs.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center space-x-2 transition-colors">
        <i class="fas fa-plus"></i>
        <span>Upload Song</span>
    </a>
</div>

<!-- Search and Filter -->
<div class="bg-gray-800 p-6 rounded-lg border border-gray-700 mb-6">
    <form method="GET" action="{{ route('songs.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search songs..." 
                   class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-500">
        </div>
        <div>
            <select name="album_id" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
                <option value="">All Albums</option>
                @foreach($albums as $albumId => $albumTitle)
                    <option value="{{ $albumId }}" {{ request('album_id') == $albumId ? 'selected' : '' }}>{{ $albumTitle }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                <i class="fas fa-search mr-2"></i>Search
            </button>
        </div>
    </form>
</div>

<!-- Songs Table -->
<div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-700">
                    <th class="text-left py-3 px-4 text-gray-300 font-semibold">Title</th>
                    <th class="text-left py-3 px-4 text-gray-300 font-semibold">Album</th>
                    <th class="text-left py-3 px-4 text-gray-300 font-semibold">Categories</th>
                    <th class="text-left py-3 px-4 text-gray-300 font-semibold">Duration</th>
                    <th class="text-left py-3 px-4 text-gray-300 font-semibold">Created</th>
                    <th class="text-center py-3 px-4 text-gray-300 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($songs as $song)
                    <tr class="border-b border-gray-700 hover:bg-gray-700 transition-colors">
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center">
                                    <i class="fas fa-music text-gray-400"></i>
                                </div>
                                <div>
                                    <p class="text-white font-medium">{{ $song->title }}</p>
                                    <p class="text-gray-400 text-sm">{{ $song->audio_file }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-gray-300">{{ $song->album->title ?? 'No Album' }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($song->categories as $category)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-700 text-gray-300">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-gray-300">
                                @php
                                    $duration = $song->duration ?? 0;
                                    $minutes = floor($duration / 60);
                                    $seconds = $duration % 60;
                                    echo sprintf('%02d:%02d', $minutes, $seconds);
                                @endphp
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-gray-400 text-sm">{{ $song->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center justify-center space-x-3">
                                <a href="{{ asset('uploads/songs/' . $song->audio_file) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 bg-green-600 hover:bg-green-700 text-white rounded transition-colors" 
                                   title="Play" target="_blank">
                                    <i class="fas fa-play text-sm"></i>
                                </a>
                                <a href="{{ route('songs.edit', $song) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 bg-yellow-600 hover:bg-yellow-700 text-white rounded transition-colors" title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <form action="{{ route('songs.destroy', $song) }}" method="POST" class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this song?')">
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
                        <td colspan="6" class="text-center py-8">
                            <div class="text-gray-400">
                                <i class="fas fa-music text-4xl mb-4"></i>
                                <p>No songs found.</p>
                                <a href="{{ route('songs.create') }}" class="text-purple-400 hover:text-purple-300 mt-2 inline-block">
                                    Upload your first song
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($songs->hasPages())
        <div class="mt-6">
            {{ $songs->links() }}
        </div>
    @endif
</div>
@endsection
