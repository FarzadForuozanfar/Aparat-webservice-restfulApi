<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    //region constant
    const PENDING  = 'pending';
    const ACCEPTED = 'accepted';
    const READ     = 'read';
    const BLOCKED  = 'blocked';

    const STATES = [self::PENDING, self::ACCEPTED, self::READ, self::BLOCKED];
    //endregion

    //region relations
    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
    //endregion

    //region custom static method
    public static function channelComments($userId)
    {
        return Comment::join('videos', 'comments.video_id',  '=', 'videos.id')->selectRaw('comments.*')->where(['videos.user_id' => $userId]);
    }
    //endregion
}
