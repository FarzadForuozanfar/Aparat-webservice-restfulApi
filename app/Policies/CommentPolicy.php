<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    public function changeState(User $user, Comment $comment, $state = null)
    {
        if ($user->id == $comment->video->user_id)
        {
            if ($comment->state == Comment::PENDING)
            {
                return $state == Comment::READ or $state == Comment::ACCEPTED;
            }
            elseif ($comment->state == Comment::READ)
            {
                return $state == Comment::ACCEPTED;
            }
        }
        return false;
    }
}
