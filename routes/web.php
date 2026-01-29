<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\ArtistProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Admin Dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->middleware('role:admin')->name('admin.dashboard');
    
    // Artist Dashboard
    Route::get('/artist/dashboard', function () {
        return view('artist.dashboard');
    })->middleware('role:artist')->name('artist.dashboard');
    
    // User Management Routes (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        
        // Category Management Routes
        Route::resource('categories', CategoryController::class);
    });
    
    // Artist Routes (Artist only)
    Route::middleware('role:artist')->group(function () {
        // Artist Profile Routes
        Route::get('/artist/profile/create', [ArtistProfileController::class, 'create'])->name('artist.profile.create');
        Route::post('/artist/profile', [ArtistProfileController::class, 'store'])->name('artist.profile.store');
        Route::get('/artist/profile/edit', [ArtistProfileController::class, 'edit'])->name('artist.profile.edit');
        Route::put('/artist/profile', [ArtistProfileController::class, 'update'])->name('artist.profile.update');
        
        // Album Management Routes
        Route::resource('albums', AlbumController::class);
        
        // Song Management Routes
        Route::resource('songs', SongController::class);
    });
});
