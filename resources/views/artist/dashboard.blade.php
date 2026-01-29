@extends('artist.layout')

@section('title', 'Artist Dashboard')

@section('header', 'Dashboard')

@section('content')
@php
    $artist = App\Models\Artist::where('user_id', auth()->id())->first();
@endphp

@if(!$artist)
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
        <div class="text-center">
            <i class="fas fa-user-music text-6xl text-gray-600 mb-4"></i>
            <h3 class="text-xl font-semibold text-white mb-2">Complete Your Artist Profile</h3>
            <p class="text-gray-400 mb-6">Please set up your artist profile to start uploading music</p>
            <a href="{{ route('artist.profile.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors">
                Create Artist Profile
            </a>
        </div>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
            <h3 class="text-lg font-semibold text-white mb-2">Total Albums</h3>
            <p class="text-3xl font-bold text-blue-400">{{ App\Models\Album::where('artist_id', $artist->id)->count() }}</p>
            <p class="text-sm text-gray-400">Your albums</p>
        </div>
        
        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
            <h3 class="text-lg font-semibold text-white mb-2">Total Songs</h3>
            <p class="text-3xl font-bold text-purple-400">{{ App\Models\Song::where('artist_id', $artist->id)->count() }}</p>
            <p class="text-sm text-gray-400">Your songs</p>
        </div>
        
        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
            <h3 class="text-lg font-semibold text-white mb-2">Stage Name</h3>
            <p class="text-3xl font-bold text-green-400">{{ $artist->stage_name }}</p>
            <p class="text-sm text-gray-400">Your artist name</p>
        </div>
        
        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
            <h3 class="text-lg font-semibold text-white mb-2">Member Since</h3>
            <p class="text-3xl font-bold text-orange-400">{{ $artist->created_at->format('M Y') }}</p>
            <p class="text-sm text-gray-400">Join date</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
            <h3 class="text-lg font-semibold text-white mb-4">Recent Albums</h3>
            <div class="space-y-3">
                @php
                    $recentAlbums = App\Models\Album::where('artist_id', $artist->id)->latest()->take(5)->get();
                @endphp
                @foreach($recentAlbums as $album)
                    <div class="flex items-center justify-between p-3 bg-gray-700 rounded">
                        <div>
                            <p class="font-medium text-white">{{ $album->title }}</p>
                            <p class="text-sm text-gray-400">{{ $album->created_at->format('M d, Y') }}</p>
                        </div>
                        <a href="{{ route('albums.show', $album) }}" class="text-blue-400 hover:text-blue-300">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                @endforeach
                @if($recentAlbums->isEmpty())
                    <div class="text-center py-4">
                        <p class="text-gray-400">No albums yet</p>
                        <a href="{{ route('albums.create') }}" class="text-blue-400 hover:text-blue-300 mt-2 inline-block">
                            Create your first album
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
            <h3 class="text-lg font-semibold text-white mb-4">Recent Songs</h3>
            <div class="space-y-3">
                @php
                    $recentSongs = App\Models\Song::where('artist_id', $artist->id)->with('album')->latest()->take(5)->get();
                @endphp
                @foreach($recentSongs as $song)
                    <div class="flex items-center justify-between p-3 bg-gray-700 rounded">
                        <div>
                            <p class="font-medium text-white">{{ $song->title }}</p>
                            <p class="text-sm text-gray-400">{{ $song->album->title ?? 'No Album' }}</p>
                        </div>
                        <a href="{{ route('songs.show', $song) }}" class="text-blue-400 hover:text-blue-300">
                            <i class="fas fa-play"></i>
                        </a>
                    </div>
                @endforeach
                @if($recentSongs->isEmpty())
                    <div class="text-center py-4">
                        <p class="text-gray-400">No songs yet</p>
                        <a href="{{ route('songs.create') }}" class="text-blue-400 hover:text-blue-300 mt-2 inline-block">
                            Upload your first song
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-6 flex justify-center space-x-4">
        <a href="{{ route('albums.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>New Album
        </a>
        <a href="{{ route('songs.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors">
            <i class="fas fa-music mr-2"></i>Upload Song
        </a>
    </div>
@endif
@endsection
