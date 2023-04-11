<?php

namespace App\Policies;

use App\Models\PlayList;
use App\Models\User;
use App\Models\Video;

class PlaylistPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function addVideo2Playlist(User $user, PlayList $playList, Video $video)
    {
        return $user->id == $playList->user_id and $video->user_id == $user->id;
    }
}
