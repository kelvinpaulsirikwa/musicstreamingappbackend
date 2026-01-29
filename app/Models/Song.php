<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'artist_id',
        'album_id',
        'title',
        'audio_file',
        'duration',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_song');
    }

    public function getAudioFileUrlAttribute()
    {
        if ($this->audio_file) {
            return url('/storage/songs/' . $this->audio_file);
        }
        return null;
    }
}
