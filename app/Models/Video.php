<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function playlist()
    {
        return $this->belongsToMany(PlayList::class, 'playlists_videos')->first();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'video_tags');
    }
}
