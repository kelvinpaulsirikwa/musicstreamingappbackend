@extends('admin.layout')

@section('title', 'Edit User')

@section('header', 'Edit User')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card p-6 rounded-xl">
        <h2 class="text-2xl font-bold text-white mb-6">Edit User</h2>
        
        <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-500/20 border border-red-500/50 rounded-lg">
                    @foreach ($errors->all() as $error)
                        <p class="text-red-200 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-white/80 text-sm font-medium mb-2">
                        Full Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:border-purple-400">
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-white/80 text-sm font-medium mb-2">
                        Username <span class="text-red-400">*</span>
                    </label>
                    <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required
                           class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:border-purple-400">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-white/80 text-sm font-medium mb-2">
                        Email Address <span class="text-red-400">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:border-purple-400">
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-white/80 text-sm font-medium mb-2">
                        Role <span class="text-red-400">*</span>
                    </label>
                    <select id="role" name="role" required
                            class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:border-purple-400">
                        <option value="">Select Role</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="artist" {{ old('role', $user->role) == 'artist' ? 'selected' : '' }}>Artist</option>
                    </select>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-white/80 text-sm font-medium mb-2">
                        New Password
                    </label>
                    <input type="password" id="password" name="password"
                           class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:border-purple-400">
                    <p class="text-white/60 text-xs mt-1">Leave blank to keep current password</p>
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-white/80 text-sm font-medium mb-2">
                        Confirm New Password
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:border-purple-400">
                </div>

                <!-- Profile Image -->
                <div class="md:col-span-2">
                    <label for="profile_image" class="block text-white/80 text-sm font-medium mb-2">
                        Profile Image
                    </label>
                    <div class="flex items-center space-x-4">
                        @if ($user->profile_image)
                            <img src="{{ asset('uploads/profiles/' . $user->profile_image) }}" alt="{{ $user->name }}" 
                                 class="w-16 h-16 rounded-full object-cover">
                        @else
                            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-xl"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <input type="file" id="profile_image" name="profile_image" accept="image/*"
                                   class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-purple-500 file:text-white hover:file:bg-purple-600">
                            <p class="text-white/60 text-xs mt-1">Optional: Upload a new profile picture (Max: 2MB)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Info -->
            <div class="mt-6 p-4 bg-white/5 rounded-lg">
                <p class="text-white/60 text-sm">
                    <i class="fas fa-info-circle mr-1"></i>
                    User ID: {{ $user->id }} | Created: {{ $user->created_at->format('M d, Y H:i') }}
                </p>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('users.index') }}" 
                   class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>Update User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
