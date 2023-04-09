<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CommentService extends BaseService
{

    public static function getAll(Request $request): array
    {
        $state    = $request->state;
        $comments = Comment::channelComments($request->user()->id);
        if ($state)
            $comments = $comments->where(['comments.state' => $state]);
        return ['data' => $comments->get(), 'total' => $comments->count()];
    }

}
