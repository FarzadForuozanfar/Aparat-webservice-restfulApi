<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use JetBrains\PhpStorm\Pure;

class Video extends Model
{
    use HasFactory, SoftDeletes;
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

    public function viewers()
    {
        return $this->belongsToMany(User::class, 'video_views')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    //endregion relations

    //region override method
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function toArray()
    {
        $data          = parent::toArray();
        $conditions    = ['user_id' => null, 'video_id' => $this->id];
        if (!auth('api')->check())
        {
            $conditions['user_ip'] = clientIP();
            $conditions['user_id'] = auth('api')->id();
        }
        $data['liked'] = VideoFavourite::where($conditions)->count();
        $data['tags']  = $this->tags;
        return $data;
    }
    //endregion override method

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

    //region custom static methods
    public static function whereRepublished()
    {
        return static::whereRaw('id IN (SELECT video_id FROM video_republishes)');
    }

    public static function whereNotRepublished()
    {
        return static::whereRaw('id NOT IN (SELECT video_id FROM video_republishes)');
    }

    /**
     * @param $userId
     * @return Builder
     */
    public static function views($userId)
    {
        return static::where('videos.user_id', $userId)
            ->Join('video_views', 'videos.id', '=', 'video_views.video_id');
    }

    /**
     * @param $userId
     * @return Builder
     */
    public static function channelComments($userId)
    {
        return static::where('videos.user_id', $userId)
            ->Join('comments', 'videos.id', '=', 'comments.video_id');
    }
    //endregion
}
