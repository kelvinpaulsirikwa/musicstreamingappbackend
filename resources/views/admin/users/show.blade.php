@extends('admin.layout')

@section('title', 'User Details')

@section('header', 'User Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card p-6 rounded-xl">
        <!-- User Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                @if ($user->profile_image)
                    <img src="{{ asset('uploads/profiles/' . $user->profile_image) }}" alt="{{ $user->name }}" 
                         class="w-20 h-20 rounded-full object-cover">
                @else
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-2xl"></i>
                    </div>
                @endif
                <div>
                    <h2 class="text-2xl font-bold text-white">{{ $user->name }}</h2>
                    <p class="text-white/80">@{{ $user->username }}</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1
                        {{ $user->role === 'admin' ? 'bg-red-500/20 text-red-400' : 'bg-blue-500/20 text-blue-400' }}">
                        <i class="fas {{ $user->role === 'admin' ? 'fa-user-shield' : 'fa-microphone' }} mr-1"></i>
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('users.edit', $user) }}" 
                   class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                @if ($user->id !== auth()->id())
                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block"
                          onsubmit="return confirm('Are you sure you want to delete this user?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition-colors">
                            <i class="fas fa-trash mr-2"></i>Delete
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- User Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-white mb-4">Basic Information</h3>
                
                <div>
                    <p class="text-white/60 text-sm">Full Name</p>
                    <p class="text-white">{{ $user->name }}</p>
                </div>
                
                <div>
                    <p class="text-white/60 text-sm">Username</p>
                    <p class="text-white">@{{ $user->username }}</p>
                </div>
                
                <div>
                    <p class="text-white/60 text-sm">Email Address</p>
                    <p class="text-white">{{ $user->email }}</p>
                </div>
                
                <div>
                    <p class="text-white/60 text-sm">Role</p>
                    <p class="text-white">{{ ucfirst($user->role) }}</p>
                </div>
            </div>

            <!-- System Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-white mb-4">System Information</h3>
                
                <div>
                    <p class="text-white/60 text-sm">User ID</p>
                    <p class="text-white">#{{ $user->id }}</p>
                </div>
                
                <div>
                    <p class="text-white/60 text-sm">Status</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $user->status === 'active' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                        <i class="fas {{ $user->status === 'active' ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                        {{ ucfirst($user->status ?? 'active') }}
                    </span>
                </div>
                
                <div>
                    <p class="text-white/60 text-sm">Created At</p>
                    <p class="text-white">{{ $user->created_at->format('M d, Y H:i:s') }}</p>
                </div>
                
                <div>
                    <p class="text-white/60 text-sm">Last Updated</p>
                    <p class="text-white">{{ $user->updated_at->format('M d, Y H:i:s') }}</p>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        @if ($user->role === 'artist' && $user->artist)
            <div class="mt-8 pt-6 border-t border-white/20">
                <h3 class="text-lg font-semibold text-white mb-4">Artist Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-white/60 text-sm">Stage Name</p>
                        <p class="text-white">{{ $user->artist->stage_name ?? 'Not set' }}</p>
                    </div>
                    <div>
                        <p class="text-white/60 text-sm">Bio</p>
                        <p class="text-white">{{ $user->artist->bio ?? 'Not set' }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="mt-8 pt-6 border-t border-white/20 flex justify-between items-center">
            <a href="{{ route('users.index') }}" 
               class="text-white/80 hover:text-white transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Users
            </a>
            
            @if ($user->id !== auth()->id())
                <button onclick="toggleUserStatus({{ $user->id }})" 
                        class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition-colors">
                    <i class="fas fa-toggle-on mr-2"></i>Toggle Status
                </button>
            @endif
        </div>
    </div>
</div>

<script>
function toggleUserStatus(userId) {
    fetch('/users/' + userId + '/toggle-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        if (data.success) {
            location.reload();
        } else {
            alert(data.error || 'Something went wrong');
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        alert('Something went wrong');
    });
}
</script>
@endsection
