<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\Artist;

class AlbumController extends Controller
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

        $albums = Album::where('artist_id', $artist->id)
            ->when($request->search, function($query, $search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('artist.albums.index', compact('albums', 'artist'));
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

        return view('artist.albums.create', compact('artist'));
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
            'release_date' => 'required|date',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['artist_id'] = $artist->id;

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('albums', $imageName, 'public');
            $data['cover_image'] = $imageName;
        }

        Album::create($data);

        return redirect()->route('albums.index')
            ->with('success', 'Album created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Album $album)
    {
        // Check if album belongs to current artist
        $artist = Artist::where('user_id', auth()->id())->first();
        if ($album->artist_id !== $artist->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('artist.albums.show', compact('album'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Album $album)
    {
        // Check if album belongs to current artist
        $artist = Artist::where('user_id', auth()->id())->first();
        if ($album->artist_id !== $artist->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('artist.albums.edit', compact('album'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Album $album)
    {
        // Check if album belongs to current artist
        $artist = Artist::where('user_id', auth()->id())->first();
        if ($album->artist_id !== $artist->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'release_date' => 'required|date',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($album->cover_image) {
                $oldImagePath = storage_path('app/public/albums/' . $album->cover_image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image = $request->file('cover_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('albums', $imageName, 'public');
            $data['cover_image'] = $imageName;
        }

        $album->update($data);

        return redirect()->route('albums.index')
            ->with('success', 'Album updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Album $album)
    {
        // Check if album belongs to current artist
        $artist = Artist::where('user_id', auth()->id())->first();
        if ($album->artist_id !== $artist->id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete cover image if exists
        if ($album->cover_image) {
            $imagePath = storage_path('app/public/albums/' . $album->cover_image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $album->delete();

        return redirect()->route('albums.index')
            ->with('success', 'Album deleted successfully.');
    }
}
