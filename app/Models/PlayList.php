<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayList extends Model
{
    use HasFactory;

    protected $table   = 'playlist';
    protected $guarded = [];

    public function videos()
    {
        return $this->belongsToMany(Video::class, 'playlists_videos');
    }
}
