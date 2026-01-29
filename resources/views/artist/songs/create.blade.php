@extends('artist.layout')

@section('title', 'Upload New Song')

@section('header', 'Upload New Song')

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
        <h2 class="text-2xl font-bold text-white mb-6">Upload New Song</h2>
        
        <form action="{{ route('songs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
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
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                           class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-500"
                           placeholder="Enter song title">
                </div>

                <!-- Album Selection -->
                <div>
                    <label for="album_id" class="block text-gray-300 text-sm font-medium mb-2">
                        Album
                    </label>
                    <select id="album_id" name="album_id" 
                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
                        <option value="">Select an album (optional)</option>
                        @foreach($albums as $albumId => $albumTitle)
                            <option value="{{ $albumId }}" {{ old('album_id') == $albumId ? 'selected' : '' }}>{{ $albumTitle }}</option>
                        @endforeach
                    </select>
                    <p class="text-gray-500 text-xs mt-1">You can create albums first if needed</p>
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
                                       {{ in_array($categoryId, old('categories', [])) ? 'checked' : '' }}
                                       class="mr-2 bg-gray-600 border-gray-500 rounded text-purple-600 focus:ring-purple-500">
                                <span class="text-sm">{{ $categoryName }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-gray-500 text-xs mt-1">Select all that apply</p>
                </div>

                <!-- Audio File Upload -->
                <div>
                    <label for="audio_file" class="block text-gray-300 text-sm font-medium mb-2">
                        Audio File <span class="text-red-400">*</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-600 rounded-lg p-6 text-center hover:border-gray-500 transition-colors">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-500 mb-4"></i>
                        <input type="file" id="audio_file" name="audio_file" accept="audio/*" required
                               class="hidden" onchange="updateFileName(this)">
                        <label for="audio_file" class="cursor-pointer">
                            <span class="text-gray-300">Click to upload or drag and drop</span>
                            <p class="text-gray-500 text-sm mt-1">MP3, WAV, OGG, M4A (max 10MB)</p>
                        </label>
                        <div id="file-name" class="mt-3 text-sm text-green-400"></div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('songs.index') }}" 
                   class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors">
                    <i class="fas fa-upload mr-2"></i>Upload Song
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function updateFileName(input) {
    const fileName = input.files[0]?.name || '';
    document.getElementById('file-name').textContent = fileName ? `Selected: ${fileName}` : '';
}

// Drag and drop functionality
const dropZone = document.querySelector('.border-dashed');
const fileInput = document.getElementById('audio_file');

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-purple-500', 'bg-gray-600');
});

dropZone.addEventListener('dragleave', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-purple-500', 'bg-gray-600');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-purple-500', 'bg-gray-600');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        updateFileName(fileInput);
    }
});
</script>
@endsection
