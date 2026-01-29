@extends('artist.layout')

@section('title', 'Albums')

@section('header', 'My Albums')

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
        <h1 class="text-2xl font-bold text-white">My Albums</h1>
        <p class="text-gray-400">Manage your album collection</p>
    </div>
    <a href="{{ route('albums.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center space-x-2 transition-colors">
        <i class="fas fa-plus"></i>
        <span>Create Album</span>
    </a>
</div>

<!-- Search -->
<div class="bg-gray-800 p-6 rounded-lg border border-gray-700 mb-6">
    <form method="GET" action="{{ route('albums.index') }}">
        <div class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search albums..." 
                   class="flex-1 px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-500">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                <i class="fas fa-search mr-2"></i>Search
            </button>
        </div>
    </form>
</div>

<!-- Albums Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse ($albums as $album)
        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 hover:border-gray-600 transition-colors">
            <div class="mb-4">
                @if ($album->cover_image)
                    <img src="{{ asset('uploads/albums/' . $album->cover_image) }}" alt="{{ $album->title }}" 
                         class="w-full h-48 object-cover rounded-lg">
                @else
                    <div class="w-full h-48 bg-gray-700 rounded-lg flex items-center justify-center">
                        <i class="fas fa-compact-disc text-6xl text-gray-600"></i>
                    </div>
                @endif
            </div>
            
            <h3 class="text-lg font-semibold text-white mb-2">{{ $album->title }}</h3>
            <p class="text-gray-400 text-sm mb-4">Released: {{ \Carbon\Carbon::parse($album->release_date)->format('M d, Y') }}</p>
            
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-400">
                    @php
                        $songCount = App\Models\Song::where('album_id', $album->id)->count();
                    @endphp
                    {{ $songCount }} songs
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('albums.show', $album) }}" 
                       class="inline-flex items-center justify-center w-8 h-8 bg-green-600 hover:bg-green-700 text-white rounded transition-colors" title="View">
                        <i class="fas fa-eye text-sm"></i>
                    </a>
                    <a href="{{ route('albums.edit', $album) }}" 
                       class="inline-flex items-center justify-center w-8 h-8 bg-yellow-600 hover:bg-yellow-700 text-white rounded transition-colors" title="Edit">
                        <i class="fas fa-edit text-sm"></i>
                    </a>
                    <form action="{{ route('albums.destroy', $album) }}" method="POST" class="inline-block"
                          onsubmit="return confirm('Are you sure you want to delete this album?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded transition-colors" title="Delete">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full">
            <div class="text-center py-12">
                <i class="fas fa-compact-disc text-6xl text-gray-600 mb-4"></i>
                <h3 class="text-xl font-semibold text-white mb-2">No albums yet</h3>
                <p class="text-gray-400 mb-6">Create your first album to start organizing your music</p>
                <a href="{{ route('albums.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Create Your First Album
                </a>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if ($albums->hasPages())
    <div class="mt-8">
        {{ $albums->links() }}
    </div>
@endif
@endsection
