<?php

namespace App\Services;

use App\Events\DeleteVideoEvent;
use App\Events\UploadNewVideo;
use App\Events\VisitVideo;
use App\Http\Requests\Video\LikeVideoRequest;
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
            try {
                if (Storage::disk('video')->exists('/tmp/' . $request->banner))
                    Storage::disk('video')->delete('/tmp/' . $request->banner);

                if (Storage::disk('video')->exists('/tmp/' . $request->video_id))
                    Storage::disk('video')->delete('/tmp/' . $request->video_id);
            }
            catch (Exception $ex)
            {
                Log::error($ex);
            }

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
            $videos = $user ? $user->videos() : Video::query()->selectRaw('*,0 as republished')->union(DB::table('videos')
                ->select('videos.*', DB::raw('1 as republished'))
                ->join('video_republishes', 'video_republishes.video_id', '=', 'videos.id')
                ->whereNull('videos.deleted_at'));
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

    public static function likeVideo(LikeVideoRequest $request)
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
        $conditions    = ['user_id' => null, 'video_id' => $request->video->id];
        if (!auth('api')->check())
        {
            $conditions['user_ip'] = clientIP();
            $conditions['user_id'] = auth('api')->id();
        }
        $videoData             = $request->video;
        $videoData['liked']    = VideoFavourite::where($conditions)->count();
        $videoData['tags']     = $videoData->tags;
        $videoData['related']  = $videoData->related()->take(5)->get(); //TODO add limit count 2 config & env
        $videoData['playlist'] = $videoData->playlist()->with('videos')->first();
        return $videoData;
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

    public static function statisticsVideo(Request $request)
    {
        $video     = $request->video;
        $from_date = now()->subDays($request->get('last_n_days', 7))->toDateString();
        $data      = [
            'views' => [],
            'total_views' => 0,

        ];
        Video::views($request->user()->id)
            ->where('videos.id' , $video->id)
            ->whereRaw("date(video_views.created_at) >= '{$from_date}'")
            ->selectRaw('date(video_views.created_at) as date, COUNT(*) as views')
            ->groupByRaw('date(video_views.created_at)')
            ->get()
            ->each(function ($item) use (&$data){
                $data['total_views'] += $item->views;
                $data['views'][$item->date] = $item->views;
            });
        return $data;
    }

    public static function updateVideo(Request $request)
    {
        $video = $request->video;
        try
        {
            DB::beginTransaction();
            $video->title               = $request->has('title') ? $request->title : $video->title;
            $video->info                = $request->has('info') ? $request->info : $video->info;
            $video->category_id         = $request->has('category') ? $request->category : $video->category_id;
            $video->channel_category_id = $request->has('channel_category') ? $request->channel_category : $video->channel_category_id;
            $video->enable_comments     = $request->has('enable_comments') ? $request->enable_comments : $video->enable_comments;

            if ($request->banner)
            {
                Storage::disk('video')->delete(auth()->id() . $video->banner);
                Storage::disk('video')->move('/tmp/' . $request->banner, auth()->id() . '/' . $video->banner);
            }

            if (!empty($request->tags))
            {
                $video->tags()->syncWithoutDetaching($request->tags);
            }
            $video->save();
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

    public static function showVideoComments(Request $request)
    {
        return sort_comments($request->video->comments()->paginate());//TODO just show accept comments & add paginate
    }

    public static function favoritesVideoList(Request $request)
    {
        $user_id = $request->user()->id;
        $videos = $request->user()
            ->favouriteVideos()
            ->selectRaw('videos.*, channels.name as channelName')
            ->leftJoin('channels', 'channels.user_id', '=', 'videos.user_id')
            ->get();
        return [
            'videos' => $videos,
            'total_favVideos' => count($videos),
            'total_comments' => Video::channelComments($request->user()->id)
                ->selectRaw('comments.*')->count(),
            'total_videos' => $request->user()->channelVideos()->count(),
            'total_views' => Video::views($user_id)->count()
        ];
    }
}
