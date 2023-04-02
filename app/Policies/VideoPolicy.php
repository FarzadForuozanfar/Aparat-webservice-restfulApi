<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;
use Illuminate\Auth\Access\Response;

class VideoPolicy
{
    public function changeState(User $user, Video $video = null)
    {
        return $user->isAdmin();
    }
}
