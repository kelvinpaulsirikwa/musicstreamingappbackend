@extends('admin.layout')

@section('title', 'Users Management')

@section('header', 'Users Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-white">Users Management</h1>
        <p class="text-gray-400">Manage all users in the system</p>
    </div>
    <a href="{{ route('users.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center space-x-2 transition-colors">
        <i class="fas fa-plus"></i>
        <span>Add New User</span>
    </a>
</div>

<!-- Search and Filter -->
<div class="bg-gray-800 p-6 rounded-lg border border-gray-700 mb-6">
    <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." 
                   class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-500">
        </div>
        <div>
            <select name="role" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-blue-500">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="artist" {{ request('role') == 'artist' ? 'selected' : '' }}>Artist</option>
            </select>
        </div>
        <div>
            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                <i class="fas fa-search mr-2"></i>Search
            </button>
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-700">
                    <th class="text-left py-3 px-4 text-gray-300 font-semibold">User</th>
                    <th class="text-left py-3 px-4 text-gray-300 font-semibold">Username</th>
                    <th class="text-left py-3 px-4 text-gray-300 font-semibold">Email</th>
                    <th class="text-left py-3 px-4 text-gray-300 font-semibold">Role</th>
                    <th class="text-left py-3 px-4 text-gray-300 font-semibold">Status</th>
                    <th class="text-left py-3 px-4 text-gray-300 font-semibold">Created</th>
                    <th class="text-center py-3 px-4 text-gray-300 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-b border-gray-700 hover:bg-gray-700 transition-colors">
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center">
                                    @if ($user->profile_image)
                                        <img src="{{ asset('uploads/profiles/' . $user->profile_image) }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <i class="fas fa-user text-gray-400"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-white font-medium">{{ $user->name }}</p>
                                    <p class="text-gray-400 text-sm">ID: {{ $user->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-gray-300">{{ $user->username }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-white">{{ $user->email }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $user->role === 'admin' ? 'bg-red-900 text-red-300' : 'bg-blue-900 text-blue-300' }}">
                                <i class="fas {{ $user->role === 'admin' ? 'fa-user-shield' : 'fa-microphone' }} mr-1"></i>
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <button onclick="toggleStatus({{ $user->id }})" 
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors
                                    {{ $user->status === 'active' ? 'bg-green-900 text-green-300 hover:bg-green-800' : 'bg-red-900 text-red-300 hover:bg-red-800' }}">
                                <i class="fas {{ $user->status === 'active' ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                {{ ucfirst($user->status ?? 'active') }}
                            </button>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-gray-400 text-sm">{{ $user->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center justify-center space-x-3">
                                <a href="{{ route('users.show', $user) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded transition-colors" title="View">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                <a href="{{ route('users.edit', $user) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 bg-yellow-600 hover:bg-yellow-700 text-white rounded transition-colors" title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                @if ($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('Are you sure you want to delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded transition-colors" title="Delete">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8">
                            <div class="text-gray-400">
                                <i class="fas fa-users text-4xl mb-4"></i>
                                <p>No users found.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($users->hasPages())
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @endif
</div>

<script>
function toggleStatus(userId) {
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
