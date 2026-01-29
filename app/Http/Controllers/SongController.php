<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\Album;
use App\Models\Artist;
use App\Models\Category;

class SongController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:artist');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $artist = Artist::where('user_id', auth()->id())->first();
        
        if (!$artist) {
            return redirect()->route('artist.dashboard')
                ->with('error', 'Please complete your artist profile first.');
        }

        $songs = Song::where('artist_id', $artist->id)
            ->with('album', 'categories')
            ->when($request->search, function($query, $search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->when($request->album_id, function($query, $albumId) {
                return $query->where('album_id', $albumId);
            })
            ->latest()
            ->paginate(10);

        $albums = Album::where('artist_id', $artist->id)->pluck('title', 'id');
        $categories = Category::pluck('name', 'id');

        return view('artist.songs.index', compact('songs', 'albums', 'categories', 'artist'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $artist = Artist::where('user_id', auth()->id())->first();
        
        if (!$artist) {
            return redirect()->route('artist.dashboard')
                ->with('error', 'Please complete your artist profile first.');
        }

        $albums = Album::where('artist_id', $artist->id)->pluck('title', 'id');
        $categories = Category::pluck('name', 'id');

        return view('artist.songs.create', compact('albums', 'categories', 'artist'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $artist = Artist::where('user_id', auth()->id())->first();
        
        if (!$artist) {
            return redirect()->route('artist.dashboard')
                ->with('error', 'Please complete your artist profile first.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'album_id' => 'nullable|exists:albums,id',
            'audio_file' => 'required|mimes:mp3,wav,ogg,m4a|max:10240', // 10MB max
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $data = $request->all();
        $data['artist_id'] = $artist->id;

        // Handle audio file upload
        if ($request->hasFile('audio_file')) {
            $audioFile = $request->file('audio_file');
            $audioFileName = time() . '_' . $audioFile->getClientOriginalName();
            $audioFile->storeAs('songs', $audioFileName, 'public');
            $data['audio_file'] = $audioFileName;
        }

        // Calculate duration (placeholder for now - in seconds)
        $data['duration'] = 180; // Default 3 minutes in seconds

        $song = Song::create($data);

        // Attach categories
        if ($request->has('categories')) {
            $song->categories()->attach($request->categories);
        }

        return redirect()->route('songs.index')
            ->with('success', 'Song uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Song $song)
    {
        // Check if song belongs to current artist
        $artist = Artist::where('user_id', auth()->id())->first();
        if ($song->artist_id !== $artist->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('artist.songs.show', compact('song'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Song $song)
    {
        // Check if song belongs to current artist
        $artist = Artist::where('user_id', auth()->id())->first();
        if ($song->artist_id !== $artist->id) {
            abort(403, 'Unauthorized action.');
        }

        $albums = Album::where('artist_id', $artist->id)->pluck('title', 'id');
        $categories = Category::pluck('name', 'id');

        return view('artist.songs.edit', compact('song', 'albums', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Song $song)
    {
        // Check if song belongs to current artist
        $artist = Artist::where('user_id', auth()->id())->first();
        if ($song->artist_id !== $artist->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'album_id' => 'nullable|exists:albums,id',
            'audio_file' => 'nullable|mimes:mp3,wav,ogg,m4a|max:10240',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $data = $request->all();

        // Handle audio file upload
        if ($request->hasFile('audio_file')) {
            // Delete old file if exists
            if ($song->audio_file) {
                $oldFilePath = storage_path('app/public/songs/' . $song->audio_file);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $audioFile = $request->file('audio_file');
            $audioFileName = time() . '_' . $audioFile->getClientOriginalName();
            $audioFile->storeAs('songs', $audioFileName, 'public');
            $data['audio_file'] = $audioFileName;
        }

        $song->update($data);

        // Sync categories
        if ($request->has('categories')) {
            $song->categories()->sync($request->categories);
        } else {
            $song->categories()->detach();
        }

        return redirect()->route('songs.index')
            ->with('success', 'Song updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Song $song)
    {
        // Check if song belongs to current artist
        $artist = Artist::where('user_id', auth()->id())->first();
        if ($song->artist_id !== $artist->id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete audio file if exists
        if ($song->audio_file) {
            $filePath = storage_path('app/public/songs/' . $song->audio_file);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Detach categories
        $song->categories()->detach();

        $song->delete();

        return redirect()->route('songs.index')
            ->with('success', 'Song deleted successfully.');
    }
}
