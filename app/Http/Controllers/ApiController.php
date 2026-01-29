<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Album;
use App\Models\Artist;
use App\Models\Song;

class ApiController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/categories",
     *      operationId="getCategories",
     *      tags={"Content"},
     *      summary="Get all categories",
     *      description="Retrieve all music categories",
     *      @OA\Response(
     *          response=200,
     *          description="Categories retrieved successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="categories", type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="id", type="integer", example=1),
     *                          @OA\Property(property="name", type="string", example="Hip Hop"),
     *                          @OA\Property(property="description", type="string", example="Hip hop music genre"),
     *                          @OA\Property(property="created_at", type="string", example="2026-01-28T10:00:00.000000Z"),
     *                          @OA\Property(property="updated_at", type="string", example="2026-01-28T10:00:00.000000Z")
     *                      )
     *                  )
     *              )
     *          )
     *      )
     * )
     */
    public function getCategories()
    {
        $categories = Category::latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'categories' => $categories
            ]
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/albums",
     *      operationId="getAlbums",
     *      tags={"Content"},
     *      summary="Get albums with pagination",
     *      description="Retrieve albums with pagination support",
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Page number",
     *          required=false,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          description="Number of items per page",
     *          required=false,
     *          @OA\Schema(type="integer", example=10)
     *      ),
     *      @OA\Parameter(
     *          name="search",
     *          in="query",
     *          description="Search albums by title",
     *          required=false,
     *          @OA\Schema(type="string", example="Album Name")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Albums retrieved successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="albums", type="object",
     *                      @OA\Property(property="data", type="array",
     *                          @OA\Items(
     *                              @OA\Property(property="id", type="integer", example=1),
     *                              @OA\Property(property="title", type="string", example="Album Title"),
     *                              @OA\Property(property="artist_id", type="integer", example=1),
     *                              @OA\Property(property="release_date", type="string", example="2026-01-15"),
     *                              @OA\Property(property="cover_image", type="string", example="album_cover.jpg"),
     *                              @OA\Property(property="created_at", type="string", example="2026-01-28T10:00:00.000000Z"),
     *                              @OA\Property(property="updated_at", type="string", example="2026-01-28T10:00:00.000000Z"),
     *                              @OA\Property(property="artist", type="object",
     *                                  @OA\Property(property="id", type="integer", example=1),
     *                                  @OA\Property(property="stage_name", type="string", example="Artist Name")
     *                              )
     *                          )
     *                      ),
     *                      @OA\Property(property="current_page", type="integer", example=1),
     *                      @OA\Property(property="first_page_url", type="string", example="http://localhost/api/albums?page=1"),
     *                      @OA\Property(property="from", type="integer", example=1),
     *                      @OA\Property(property="last_page", type="integer", example=5),
     *                      @OA\Property(property="last_page_url", type="string", example="http://localhost/api/albums?page=5"),
     *                      @OA\Property(property="next_page_url", type="string", example="http://localhost/api/albums?page=2"),
     *                      @OA\Property(property="path", type="string", example="http://localhost/api/albums"),
     *                      @OA\Property(property="per_page", type="integer", example=10),
     *                      @OA\Property(property="prev_page_url", type="string", example=null),
     *                      @OA\Property(property="to", type="integer", example=10),
     *                      @OA\Property(property="total", type="integer", example=50)
     *                  )
     *              )
     *          )
     *      )
     * )
     */
    public function getAlbums(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');

        $albums = Album::with('artist')
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage);

        // Append image_url to artists and cover_image_url to albums
        $albums->setCollection(
            $albums->getCollection()->map(function ($album) {
                if ($album->artist) {
                    $album->artist->append('image_url');
                }
                $album->append('cover_image_url');
                return $album;
            })
        );

        return response()->json([
            'status' => 'success',
            'data' => [
                'albums' => $albums
            ]
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/albums/{id}",
     *      operationId="getAlbumById",
     *      tags={"Content"},
     *      summary="Get album by ID",
     *      description="Retrieve a specific album by its ID",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Album ID",
     *          required=true,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Album retrieved successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="album", type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="title", type="string", example="Album Title"),
     *                      @OA\Property(property="artist_id", type="integer", example=1),
     *                      @OA\Property(property="release_date", type="string", example="2026-01-15"),
     *                      @OA\Property(property="cover_image", type="string", example="album_cover.jpg"),
     *                      @OA\Property(property="created_at", type="string", example="2026-01-28T10:00:00.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", example="2026-01-28T10:00:00.000000Z"),
     *                      @OA\Property(property="artist", type="object",
     *                          @OA\Property(property="id", type="integer", example=1),
     *                          @OA\Property(property="stage_name", type="string", example="Artist Name")
     *                      )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Album not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Album not found")
     *          )
     *      )
     * )
     */
    public function getAlbumById($id)
    {
        $album = Album::with('artist')->find($id);

        if (!$album) {
            return response()->json([
                'status' => 'error',
                'message' => 'Album not found'
            ], 404);
        }

        // Append image_url to artist and cover_image_url to album
        if ($album->artist) {
            $album->artist->append('image_url');
        }
        $album->append('cover_image_url');

        return response()->json([
            'status' => 'success',
            'data' => [
                'album' => $album
            ]
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/songs/category/{categoryId}",
     *      operationId="getSongsByCategory",
     *      tags={"Music"},
     *      summary="Get songs by category with pagination",
     *      description="Retrieve songs filtered by category with pagination support",
     *      @OA\Parameter(
     *          name="categoryId",
     *          in="path",
     *          description="Category ID",
     *          required=true,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Page number",
     *          required=false,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          description="Number of items per page",
     *          required=false,
     *          @OA\Schema(type="integer", example=10)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Songs retrieved successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="songs", type="object",
     *                      @OA\Property(property="data", type="array",
     *                          @OA\Items(
     *                              @OA\Property(property="id", type="integer", example=1),
     *                              @OA\Property(property="title", type="string", example="Song Title"),
     *                              @OA\Property(property="artist_id", type="integer", example=1),
     *                              @OA\Property(property="album_id", type="integer", example=1),
     *                              @OA\Property(property="audio_file", type="string", example="song.mp3"),
     *                              @OA\Property(property="duration", type="integer", example=180),
     *                              @OA\Property(property="created_at", type="string", example="2026-01-28T10:00:00.000000Z"),
     *                              @OA\Property(property="artist", type="object",
     *                                  @OA\Property(property="id", type="integer", example=1),
     *                                  @OA\Property(property="stage_name", type="string", example="Artist Name")
     *                              ),
     *                              @OA\Property(property="album", type="object",
     *                                  @OA\Property(property="id", type="integer", example=1),
     *                                  @OA\Property(property="title", type="string", example="Album Title")
     *                              ),
     *                              @OA\Property(property="categories", type="array",
     *                                  @OA\Items(
     *                                      @OA\Property(property="id", type="integer", example=1),
     *                                      @OA\Property(property="name", type="string", example="Hip Hop")
     *                                  )
     *                              )
     *                          )
     *                      ),
     *                      @OA\Property(property="current_page", type="integer", example=1),
     *                      @OA\Property(property="last_page", type="integer", example=5),
     *                      @OA\Property(property="per_page", type="integer", example=10),
     *                      @OA\Property(property="total", type="integer", example=50)
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Category not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Category not found")
     *          )
     *      )
     * )
     */
    public function getSongsByCategory(Request $request, $categoryId)
    {
        $category = Category::find($categoryId);
        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found'
            ], 404);
        }

        $perPage = $request->get('per_page', 10);

        $songs = Song::with(['artist', 'album', 'categories'])
            ->whereHas('categories', function ($query) use ($categoryId) {
                $query->where('categories.id', $categoryId);
            })
            ->latest()
            ->paginate($perPage);

        // Append image_url to artists, cover_image_url to albums, and audio_file_url to songs
        $songs->setCollection(
            $songs->getCollection()->map(function ($song) {
                if ($song->artist) {
                    $song->artist->append('image_url');
                }
                if ($song->album) {
                    $song->album->append('cover_image_url');
                }
                $song->append('audio_file_url');
                return $song;
            })
        );

        return response()->json([
            'status' => 'success',
            'data' => [
                'category' => $category,
                'songs' => $songs
            ]
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/songs/album/{albumId}",
     *      operationId="getSongsByAlbum",
     *      tags={"Music"},
     *      summary="Get songs by album with pagination",
     *      description="Retrieve songs filtered by album with pagination support",
     *      @OA\Parameter(
     *          name="albumId",
     *          in="path",
     *          description="Album ID",
     *          required=true,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Page number",
     *          required=false,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          description="Number of items per page",
     *          required=false,
     *          @OA\Schema(type="integer", example=10)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Songs retrieved successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="album", type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="title", type="string", example="Album Title"),
     *                      @OA\Property(property="artist", type="object",
     *                          @OA\Property(property="id", type="integer", example=1),
     *                          @OA\Property(property="stage_name", type="string", example="Artist Name")
     *                      )
     *                  ),
     *                  @OA\Property(property="songs", type="object",
     *                      @OA\Property(property="data", type="array",
     *                          @OA\Items(
     *                              @OA\Property(property="id", type="integer", example=1),
     *                              @OA\Property(property="title", type="string", example="Song Title"),
     *                              @OA\Property(property="artist_id", type="integer", example=1),
     *                              @OA\Property(property="album_id", type="integer", example=1),
     *                              @OA\Property(property="audio_file", type="string", example="song.mp3"),
     *                              @OA\Property(property="duration", type="integer", example=180),
     *                              @OA\Property(property="created_at", type="string", example="2026-01-28T10:00:00.000000Z"),
     *                              @OA\Property(property="artist", type="object",
     *                                  @OA\Property(property="id", type="integer", example=1),
     *                                  @OA\Property(property="stage_name", type="string", example="Artist Name")
     *                              ),
     *                              @OA\Property(property="categories", type="array",
     *                                  @OA\Items(
     *                                      @OA\Property(property="id", type="integer", example=1),
     *                                      @OA\Property(property="name", type="string", example="Hip Hop")
     *                                  )
     *                              )
     *                          )
     *                      ),
     *                      @OA\Property(property="current_page", type="integer", example=1),
     *                      @OA\Property(property="last_page", type="integer", example=2),
     *                      @OA\Property(property="per_page", type="integer", example=10),
     *                      @OA\Property(property="total", type="integer", example=15)
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Album not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Album not found")
     *          )
     *      )
     * )
     */
    public function getSongsByAlbum(Request $request, $albumId)
    {
        $album = Album::with('artist')->find($albumId);
        if (!$album) {
            return response()->json([
                'status' => 'error',
                'message' => 'Album not found'
            ], 404);
        }

        // Append image_url to album artist
        if ($album->artist) {
            $album->artist->append('image_url');
        }

        $perPage = $request->get('per_page', 10);

        $songs = Song::with(['artist', 'categories'])
            ->where('album_id', $albumId)
            ->latest()
            ->paginate($perPage);

        // Append image_url to song artists and audio_file_url to songs
        $songs->setCollection(
            $songs->getCollection()->map(function ($song) {
                if ($song->artist) {
                    $song->artist->append('image_url');
                }
                $song->append('audio_file_url');
                return $song;
            })
        );

        return response()->json([
            'status' => 'success',
            'data' => [
                'album' => $album,
                'songs' => $songs
            ]
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/songs/recent",
     *      operationId="getRecentSongs",
     *      tags={"Music"},
     *      summary="Get recent songs with pagination",
     *      description="Retrieve recently added songs with pagination support",
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Page number",
     *          required=false,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          description="Number of items per page",
     *          required=false,
     *          @OA\Schema(type="integer", example=10)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Recent songs retrieved successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="songs", type="object",
     *                      @OA\Property(property="data", type="array",
     *                          @OA\Items(
     *                              @OA\Property(property="id", type="integer", example=1),
     *                              @OA\Property(property="title", type="string", example="Song Title"),
     *                              @OA\Property(property="artist_id", type="integer", example=1),
     *                              @OA\Property(property="album_id", type="integer", example=1),
     *                              @OA\Property(property="audio_file", type="string", example="song.mp3"),
     *                              @OA\Property(property="duration", type="integer", example=180),
     *                              @OA\Property(property="created_at", type="string", example="2026-01-28T10:00:00.000000Z"),
     *                              @OA\Property(property="artist", type="object",
     *                                  @OA\Property(property="id", type="integer", example=1),
     *                                  @OA\Property(property="stage_name", type="string", example="Artist Name")
     *                              ),
     *                              @OA\Property(property="album", type="object",
     *                                  @OA\Property(property="id", type="integer", example=1),
     *                                  @OA\Property(property="title", type="string", example="Album Title")
     *                              ),
     *                              @OA\Property(property="categories", type="array",
     *                                  @OA\Items(
     *                                      @OA\Property(property="id", type="integer", example=1),
     *                                      @OA\Property(property="name", type="string", example="Hip Hop")
     *                                  )
     *                              )
     *                          )
     *                      ),
     *                      @OA\Property(property="current_page", type="integer", example=1),
     *                      @OA\Property(property="last_page", type="integer", example=10),
     *                      @OA\Property(property="per_page", type="integer", example=10),
     *                      @OA\Property(property="total", type="integer", example=100)
     *                  )
     *              )
     *          )
     *      )
     * )
     */
    public function getRecentSongs(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $songs = Song::with(['artist', 'album', 'categories'])
            ->latest()
            ->paginate($perPage);

        // Append image_url to artists, cover_image_url to albums, and audio_file_url to songs
        $songs->setCollection(
            $songs->getCollection()->map(function ($song) {
                if ($song->artist) {
                    $song->artist->append('image_url');
                }
                if ($song->album) {
                    $song->album->append('cover_image_url');
                }
                $song->append('audio_file_url');
                return $song;
            })
        );

        return response()->json([
            'status' => 'success',
            'data' => [
                'songs' => $songs
            ]
        ]);
    }
}
