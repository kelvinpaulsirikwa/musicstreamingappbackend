@extends('admin.layout')

@section('title', 'Admin Dashboard')

@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
        <h3 class="text-lg font-semibold text-white mb-2">Total Users</h3>
        <p class="text-3xl font-bold text-blue-400">{{ App\Models\User::count() }}</p>
        <p class="text-sm text-gray-400">Registered users</p>
    </div>
    
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
        <h3 class="text-lg font-semibold text-white mb-2">Artists</h3>
        <p class="text-3xl font-bold text-purple-400">{{ App\Models\User::where('role', 'artist')->count() }}</p>
        <p class="text-sm text-gray-400">Artist accounts</p>
    </div>
    
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
        <h3 class="text-lg font-semibold text-white mb-2">Categories</h3>
        <p class="text-3xl font-bold text-green-400">{{ App\Models\Category::count() }}</p>
        <p class="text-sm text-gray-400">Music categories</p>
    </div>
    
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
        <h3 class="text-lg font-semibold text-white mb-2">Admins</h3>
        <p class="text-3xl font-bold text-orange-400">{{ App\Models\User::where('role', 'admin')->count() }}</p>
        <p class="text-sm text-gray-400">Admin accounts</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
        <h3 class="text-lg font-semibold text-white mb-4">Recent Users</h3>
        <div class="space-y-3">
            @php
                $recentUsers = App\Models\User::latest()->take(5)->get();
            @endphp
            @foreach($recentUsers as $user)
                <div class="flex items-center justify-between p-3 bg-gray-700 rounded">
                    <div>
                        <p class="font-medium text-white">{{ $user->name }}</p>
                        <p class="text-sm text-gray-400">{{ $user->email }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                            {{ $user->role === 'admin' ? 'bg-red-900 text-red-300' : 'bg-blue-900 text-blue-300' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                        <p class="text-xs text-gray-400 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
        <h3 class="text-lg font-semibold text-white mb-4">Categories</h3>
        <div class="space-y-3">
            @php
                $categories = App\Models\Category::latest()->take(5)->get();
            @endphp
            @foreach($categories as $category)
                <div class="flex items-center justify-between p-3 bg-gray-700 rounded">
                    <div>
                        <p class="font-medium text-white">{{ $category->name }}</p>
                        @if($category->description)
                            <p class="text-sm text-gray-400">{{ Str::limit($category->description, 50) }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-400">{{ $category->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
