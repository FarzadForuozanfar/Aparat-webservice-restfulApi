<?php

namespace App\Policies;

use App\Models\RepublishVideo;
use App\Models\User;
use App\Models\Video;

class VideoPolicy
{

    public function changeState(User $user, Video $video = null)
    {
        return $user->isAdmin();
    }

    public function republish(User $user, Video $video = null): bool
    {
        return $video &&
            (
                // در صورتی که این ویدیو مال خودم نباشد
                $video->user_id != $user->id &&
                // در صورتی که قبلا این ویدیو توسط من بازنشر نشده باشد
                RepublishVideo::where([
                    'user_id' => $user->id,
                    'video_id' => $video->id
                ])->count() < 1
            );
    }
}
