@extends('artist.layout')

@section('title', 'Create Artist Profile')

@section('header', 'Create Artist Profile')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
        <h2 class="text-2xl font-bold text-white mb-6">Create Your Artist Profile</h2>
        <p class="text-gray-400 mb-6">Set up your artist profile to start uploading music and managing your catalog.</p>
        
        <form action="{{ route('artist.profile.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-900 border border-red-700 rounded-lg">
                    @foreach ($errors->all() as $error)
                        <p class="text-red-300 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="space-y-6">
                <!-- Stage Name -->
                <div>
                    <label for="stage_name" class="block text-gray-300 text-sm font-medium mb-2">
                        Stage Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" id="stage_name" name="stage_name" value="{{ old('stage_name') }}" required
                           class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-500"
                           placeholder="e.g., The Beatles, Drake, Taylor Swift">
                    <p class="text-gray-500 text-xs mt-1">This is how fans will find you</p>
                </div>

                <!-- Bio -->
                <div>
                    <label for="bio" class="block text-gray-300 text-sm font-medium mb-2">
                        Bio
                    </label>
                    <textarea id="bio" name="bio" rows="4"
                              class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-500"
                              placeholder="Tell your fans about yourself, your music style, and what inspires you...">{{ old('bio') }}</textarea>
                    <p class="text-gray-500 text-xs mt-1">Maximum 1000 characters</p>
                </div>

                <!-- Profile Image -->
                <div>
                    <label for="image" class="block text-gray-300 text-sm font-medium mb-2">
                        Profile Picture
                    </label>
                    <div class="flex items-center space-x-4">
                        <div class="w-20 h-20 bg-gray-700 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-gray-500 text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <input type="file" id="image" name="image" accept="image/*"
                                   class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-purple-600 file:text-white hover:file:bg-purple-700">
                            <p class="text-gray-500 text-xs mt-1">Optional: Upload a profile picture (Max: 2MB, JPG/PNG)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('artist.dashboard') }}" 
                   class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors">
                    <i class="fas fa-user mr-2"></i>Create Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
