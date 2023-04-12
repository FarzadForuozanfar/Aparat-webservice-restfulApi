<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use JetBrains\PhpStorm\Pure;

class Video extends Model
{
    use HasFactory, SoftDeletes;
    //region const var
    const PENDING   = 'pending'; // پردازش در صف
    const CONVERTED = 'converted'; // تبدیل انجام شده
    const ACCEPT    = 'accept'; // پذیرفته شده و انشار یافته
    const BLOCKED   = 'blocked'; // محتوا ویدیو مناسب نبود
    const STATES    = [self::PENDING, self::CONVERTED, self::ACCEPT, self::BLOCKED];
    //endregion
    protected $guarded = [];

    //region getters
    public function getVideoLinkAttr()
    {
        return Storage::disk('video')->url($this->user_id . '/' . $this->slug . '.mp4');
    }

    public function getVideoBannerLinkAttr()
    {
        return Storage::disk('video')->url($this->user_id . '/' . $this->slug . '_banner');
    }
    //endregion

    //region relations
    public function playlist()
    {
        return $this->belongsToMany(PlayList::class, 'playlists_videos');
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

    public function related()
    {
        return static::selectRaw('COUNT(*) related_tags, videos.*')
            ->leftJoin('video_tags', 'videos.id', '=', 'video_tags.video_id')
            ->whereRaw('videos.id != ' . $this->id)
            ->whereRaw("videos.state = '" . self::ACCEPT . "'")
            ->whereIn(DB::raw('video_tags.tag_id'), function ($query) {
                $query->selectRaw('video_tags.tag_id')
                    ->from('videos')
                    ->leftJoin('video_tags', 'videos.id', '=', 'video_tags.video_id')
                    ->whereRaw('videos.id=' . $this->id);
            })
            ->groupBy(DB::raw('videos.id'))
            ->orderBy('related_tags', 'desc');
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
        $data['views'] = VideoView::where(['video_id' => $this->id])->count();
        $data['link']  = $this->getVideoLinkAttr();
        $data['banner_link']  = $this->getVideoBannerLinkAttr();

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
