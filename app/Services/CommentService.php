<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public static function createComment(Request $request)
    {
        try {
            $video   = Video::find($request->video_id);
            $comment = $request->user()->comments()->create([
                'video_id' => $request->video_id,
                'parent_id' => $request->parent_id,
                'body' => $request->body,
                'state' => $video->user_id == $request->user()->id ? Comment::ACCEPTED : Comment::PENDING
            ]);

            return response(['data' => $comment], 200);
        }
        catch (\Exception $exception)
        {
            Log::error($exception);
            return response(['message' => 'خطا رخ داده است' . $exception->getMessage()], 500);
        }
    }

    public static function changeState(Request $request)
    {
        try {
            $comment        = $request->comment;
            $comment->state = $request->state;
            $comment->save();

            return response(['message' => 'با موفقیت تغییر وضعیت به ' . $request->state . ' داده شد '], 200);
        }
        catch (\Exception $exception)
        {
            Log::error($exception);
            return response(['message' => $exception->getMessage()], 500);
        }
    }

    public static function delete(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->comment->delete();
            DB::commit();
            return response(['message' => 'با موفقیت پاک شد'], 200);
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            Log::error($exception, $request->comment);
            return response(['message' => $exception->getMessage()], 500);
        }
    }

}
