<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JetBrains\PhpStorm\Pure;

class Video extends Model
{
    use HasFactory;
    const PENDING   = 'pending'; // پردازش در صف
    const CONVERTED = 'converted'; // تبدیل انجام شده
    const ACCEPT    = 'accept'; // پذیرفته شده و انشار یافته
    const BLOCKED   = 'blocked'; // محتوا ویدیو مناسب نبود
    const STATES    = [self::PENDING, self::CONVERTED, self::ACCEPT, self::BLOCKED];
    protected $guarded = [];

    //region relations
    public function playlist()
    {
        return $this->belongsToMany(PlayList::class, 'playlists_videos')->first();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'video_tags');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //endregion relations

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    //region custom method

    /**
     * @param $state
     * @return bool
     */
    public function isInState($state): bool
    {
        return $this->state == $state;
    }
    #[Pure] public function isAccepted(): bool
    {
        return $this->isInState(self::ACCEPT);
    }
    #[Pure] public function isPending(): bool
    {
        return $this->isInState(self::PENDING);
    }
    #[Pure] public function isConverted(): bool
    {
        return $this->isInState(self::CONVERTED);
    }
    #[Pure] public function isBlocked(): bool
    {
        return $this->isInState(self::BLOCKED);
    }
    //endregion custom method
}
