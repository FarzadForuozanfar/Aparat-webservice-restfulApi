<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    const PENDING   = 'pending'; // پردازش در صف
    const CONVERTED = 'converted'; // تبدیل انجام شده
    const ACCEPT    = 'accept'; // پذیرفته شده و انشار یافته
    const BLOCKED   = 'blocked'; // محتوا ویدیو مناسب نبود
    const STATES    = [self::PENDING, self::CONVERTED, self::ACCEPT, self::BLOCKED];
    protected $guarded = [];

    public function playlist()
    {
        return $this->belongsToMany(PlayList::class, 'playlists_videos')->first();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'video_tags');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
