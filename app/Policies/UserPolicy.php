<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function follow(User $user, User $otherUser): bool
    {
        return $user->id != $otherUser->id and !$user->followings()->where('user_id2', $otherUser->id)->count();
    }

    public function unfollow(User $user, User $otherUser): bool
    {
        return $user->id != $otherUser->id and $user->followings()->where('user_id2', $otherUser->id)->count();
    }
}
