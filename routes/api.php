<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [ApiAuthController::class, 'login']);

// Public content routes
Route::get('/categories', [ApiController::class, 'getCategories']);
Route::get('/albums', [ApiController::class, 'getAlbums']);
Route::get('/albums/{id}', [ApiController::class, 'getAlbumById']);

// Music routes (public)
Route::get('/songs/category/{categoryId}', [ApiController::class, 'getSongsByCategory']);
Route::get('/songs/album/{albumId}', [ApiController::class, 'getSongsByAlbum']);
Route::get('/songs/recent', [ApiController::class, 'getRecentSongs']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ApiAuthController::class, 'profile']);
    Route::put('/profile', [ApiAuthController::class, 'updateProfile']);
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
