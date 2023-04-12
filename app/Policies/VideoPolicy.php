<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\RepublishVideo;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoFavourite;

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
        if ($video and $video->isAccepted()) {
            $conditions = [
                'user_id' => $user?->id,
                'video_id' => $video->id
            ];
            if (empty($user))
                $conditions['user_ip'] = clientIP();

            return VideoFavourite::where($conditions)->count() == 0;
        }
        return false;
    }

    /**
     * @param User|null $user
     * @param Video|null $video
     * @return bool
     */
    public function unlike(User $user = null, Video $video = null): bool
    {
        if ($video and $video->isAccepted()) {
            $conditions = [
                'user_id' => $user?->id,
                'video_id' => $video->id
            ];
            if (empty($user))
                $conditions['user_ip'] = clientIP();

            return (bool)VideoFavourite::where($conditions)->count();
        }
        return false;
    }

    /**
     * @param User $user
     * @param Video|null $video
     * @return bool
     */
    public function OwnLikedList(User $user, Video $video = null): bool
    {
        return isset($user);
    }

    public function deleteVideo(User $user, Video $video): bool
    {
        return $user->id == $video->user_id;
    }

    public function ShowStatistics(User $user, Video $video): bool
    {
        return $user->id == $video->user_id;
    }

    public function update(User $user, Video $video): bool
    {
        return $user->id == $video->user_id;
    }

    public function createComment(User $user, Video $video, $parent_id = null)
    {
        if ($parent_id)
        {
            $video_parent = Comment::find($parent_id)->first();
            return $video_parent->id == $video->id and $video->state == Video::ACCEPT;
        }
        return $video->state == Video::ACCEPT;
    }
}
