<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;

class ArtistProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:artist');
    }

    /**
     * Show the form for creating a new artist profile.
     */
    public function create()
    {
        // Check if artist profile already exists
        $existingProfile = Artist::where('user_id', auth()->id())->first();
        if ($existingProfile) {
            return redirect()->route('artist.dashboard')
                ->with('success', 'Artist profile already exists.');
        }

        return view('artist.profile.create');
    }

    /**
     * Store a newly created artist profile in storage.
     */
    public function store(Request $request)
    {
        // Check if artist profile already exists
        $existingProfile = Artist::where('user_id', auth()->id())->first();
        if ($existingProfile) {
            return redirect()->route('artist.dashboard')
                ->with('error', 'Artist profile already exists.');
        }

        $request->validate([
            'stage_name' => 'required|string|max:255|unique:artists',
            'bio' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();

        // Handle profile image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('artists', $imageName, 'public');
            $data['image'] = $imageName;
        }

        Artist::create($data);

        return redirect()->route('artist.dashboard')
            ->with('success', 'Artist profile created successfully!');
    }

    /**
     * Show the form for editing the artist profile.
     */
    public function edit()
    {
        $artist = Artist::where('user_id', auth()->id())->first();
        
        if (!$artist) {
            return redirect()->route('artist.profile.create')
                ->with('error', 'Please create your artist profile first.');
        }

        return view('artist.profile.edit', compact('artist'));
    }

    /**
     * Update the artist profile in storage.
     */
    public function update(Request $request)
    {
        $artist = Artist::where('user_id', auth()->id())->first();
        
        if (!$artist) {
            return redirect()->route('artist.profile.create')
                ->with('error', 'Please create your artist profile first.');
        }

        $request->validate([
            'stage_name' => 'required|string|max:255|unique:artists,stage_name,' . $artist->id,
            'bio' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Handle profile image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($artist->image) {
                $oldImagePath = storage_path('app/public/artists/' . $artist->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('artists', $imageName, 'public');
            $data['image'] = $imageName;
        }

        $artist->update($data);

        return redirect()->route('artist.dashboard')
            ->with('success', 'Artist profile updated successfully!');
    }
}
