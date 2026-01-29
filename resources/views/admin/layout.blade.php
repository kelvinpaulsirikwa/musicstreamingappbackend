<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - MusicStream</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-900">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white">
            <div class="p-4">
                <h1 class="text-xl font-bold">MusicStream Admin</h1>
            </div>
            
            <nav class="mt-8">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 {{ request()->is('admin/dashboard') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                    Dashboard
                </a>
                <a href="{{ route('users.index') }}" class="block px-4 py-2 {{ request()->is('users*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                    Users
                </a>
                <a href="{{ route('categories.index') }}" class="block px-4 py-2 {{ request()->is('categories*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                    Categories
                </a>
            </nav>
            
            <div class="absolute bottom-0 w-64 p-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-700">
                        Logout
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 bg-gray-900 p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-white">@yield('header', 'Dashboard')</h2>
                    <p class="text-gray-400">Welcome back, {{ Auth::user()->name }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-400">Administrator</p>
                </div>
            </div>
            
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-900 border border-green-700 text-green-300 rounded">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-900 border border-red-700 text-red-300 rounded">
                    {{ session('error') }}
                </div>
            @endif
            
            <!-- Main Content Area -->
            @yield('content')
        </div>
    </div>
</body>
</html>
