<?php

namespace App\Policies;

use App\Models\RepublishVideo;
use App\Models\User;
use App\Models\Video;

class VideoPolicy
{

    /**
     * @param User $user
     * @param Video|null $video
     * @return bool
     */
    public function changeState(User $user, Video $video = null)
    {
        return $user->isAdmin();
    }

    public function republish(User $user, Video $video = null): bool
    {
        return $video && $video->isAccepted() and
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

    /**
     * @param User|null $user
     * @param Video|null $video
     * @return bool
     */
    public function like(User $user = null, Video $video = null): bool
    {
        return $video and $video->isAccepted();
    }

    /**
     * @param User|null $user
     * @param Video|null $video
     * @return bool
     */
    public function OwnLikedList(User $user, Video $video = null): bool
    {
        return isset($user);
    }

}
