<?php

namespace App\Services;

use App\Events\DeleteVideoEvent;
use App\Events\UploadNewVideo;
use App\Events\VisitVideo;
use App\Models\PlayList;
use App\Models\RepublishVideo;
use App\Models\Video;
use App\Models\VideoFavourite;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class VideoService extends BaseService
{
    public static function uploadVideo(Request $request)
    {
        try
        {
            $video    = $request->file('video');
            $fileName = time() . Str::random(24);
            $path     = public_path('videos/tmp');
            $video->move($path , $fileName);

            return response(['video' => $fileName], 200);
        }
        catch (Exception $exception)
        {
            Log::error($exception);
            return response(['message' => $exception->getMessage()], 500);
        }
    }

    public static function createVideo(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $video = Video::create([
                'title' => $request->title,
                'category_id' => $request->category,
                'user_id' => auth()->id(),
                'slug' => '',
                'info' => $request->info,
                'duration' => 0,
                'banner' => '',
                'enable_comments' => $request->enable_comments,
                'publish_at' => $request->publish_at,
                'channel_category_id' => $request->channel_category,
                'state'=>Video::PENDING
            ]);

            $video->slug   = uniqueId($video->id);
            $video->banner = $video->slug . '_banner';
            $video->save();

            event(new UploadNewVideo($video, $request));
            if ($request->banner)
            {
                Storage::disk('video')->move('/tmp/' . $request->banner, auth()->id() . '/' . $video->banner);
            }

            if ($request->playlist)
            {
                $playlist = PlayList::find($request->playlist);
                $playlist->videos()->attach($video->id);
            }

            if (!empty($request->tags))
            {
                $video->tags()->attach($request->tags);
            }

            DB::commit();
            return response(['message' => 'ویدیو با موفقیت آپلود شد', 'data' => $video], 200);
        }
        catch (Exception $exception)
        {
            //TODO delete banner & video if exist in directory
            Log::error($request.$exception);
            DB::rollBack();
            return response(['message' => 'خطا رخ داده است'], 500);
        }
    }

    public static function uploadVideoBanner(Request $request)
    {
        try
        {
            $banner   = $request->file('banner');
            $fileName = time() . Str::random(24) . '_banner';
            $path     = public_path('videos/tmp');
            $banner->move($path , $fileName);

            return response(['banner' => $fileName], 200);
        }
        catch (Exception $exception)
        {
            Log::error($exception);
            return response(['message' => $exception->getMessage()], 500);
        }
    }

    public static function changeState(Request $request)
    {
        $video        = $request->video;
        $video->state = $request->state;
        $video->save();
        return response($video, 200);
    }

    public static function list(Request $request)
    {
        $user = auth('api')->user();
        if ($request->has('republished'))
        {
           if ($user)
           {
               $videos = (bool)$request->republished ? $user->republishedVideos() : $user->channelVideos();
           }
           else
           {
                $videos = (bool)$request->republished ? Video::whereRepublished() : Video::whereNotRepublished();
           }
        }
        else
        {
            $videos = $user ? $user->videos() : Video::query()->union(RepublishVideo::query()); //TODO درصورت لاگین نبودن یوزر دومی اجرا می شود ولی تمام ویدیو ها را میده باید با بازنشر شده ها جوین بزنه
        }

        return $videos->orderByDesc('updated_at')->paginate();//TODO define size of paginate for video in config
    }

    public static function republish(Request $request)
    {
        try {
            $user           = auth()->user();
            $videoRepublish = RepublishVideo::create([
                'user_id' => $user->id,
                'video_id' => $request->video->id
            ]);
            return response(['message' => 'ویدیو با موفقیت بازنشر شد', 'data' => $videoRepublish->created_at], 200);
        }
        catch (Exception $exception)
        {
            Log::error($exception);
            return response(['message' => 'خطا رخ داده است' . $exception->getMessage()], 500);
        }
    }

    public static function likedByCurrentUser(Request $request)
    {
        $user   = $request->user();
        return $user->favouriteVideos()->paginate();
    }

    public static function likeVideo(Request $request)
    {
        try {
            VideoFavourite::create(['user_id' => auth('api')->id(),
                    'video_id'=> $request->video->id,
                    'user_ip' => (string)clientIP()]
            );
            return response(['message' => 'ویدیو با موفقیت لایک شد'],200);
        }
        catch (Exception $exception)
        {
            Log::error($exception);
            response(['message' => 'با خطا مواجه شد' . $exception->getMessage()], 500);
        }
    }

    public static function unLikeVideo(Request $request)
    {
        try {
            $conditions = [
                'user_id' => auth('api')->id() ? auth('api')->id() : null,
                'video_id' =>  $request->video->id
            ];
            if (empty($user))
                $conditions['user_ip'] = clientIP();

            VideoFavourite::where($conditions)->delete();
            return response(['message' => 'ویدیو با موفقیت unlike شد'],200);
        }
        catch (Exception $exception)
        {
            Log::error($exception);
            response(['message' => 'با خطا مواجه شد' . $exception->getMessage()], 500);
        }
    }

    public static function showVideo(Request $request)
    {
        event(new VisitVideo($request->video));
        return $request->video;
    }

    public static function deleteVideo(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->video->forceDelete();
            event(new DeleteVideoEvent($request->video));
            DB::commit();
            return response(['message' => 'ویدیو با موفقیت حذف شد'],200);
        }
        catch (Exception $exception)
        {
            DB::rollBack();
            Log::error($exception);
            return response(['message' => $exception->getMessage()],500);
        }
    }
}
