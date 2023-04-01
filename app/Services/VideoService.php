<?php

namespace App\Services;

use App\Models\PlayList;
use App\Models\User;
use App\Models\Video;
use Exception;
use FFMpeg\Filters\Video\CustomFilter;
use FFMpeg\Filters\Video\VideoFilters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ProtoneMedia\LaravelFFMpeg\Filesystem\Media;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

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
            /** @var Media $videoFile */
            $tmpPath       = '/tmp/' . $request->video_id;
            $channelName   = auth()->user()->channel->name;
            $videoFile     = FFMpeg::fromDisk('video')->open($tmpPath);
            $filter        = new CustomFilter("drawtext=text='aparat.me/{$channelName}': fontcolor=blue: fontsize=24:
            box=1: boxcolor=white@0.4: boxborderw=5:
            x=10: y=(h - text_h - 10)");
            $format    = new \FFMpeg\Format\Video\X264('libmp3lame');
            $videoFile = $videoFile->addFilter($filter)->export()->toDisk('video')->inFormat($format);
            DB::beginTransaction();
            $video = Video::create([
                'title' => $request->title,
                'category_id' => $request->category,
                'user_id' => auth()->id(),
                'slug' => '',
                'info' => $request->info,
                'duration' => (int)$videoFile->getDurationInSeconds(),
                'banner' => '',
                'enable_comments' => $request->enable_comments,
                'publish_at' => $request->publish_at,
                'channel_category_id' => $request->channel_category
            ]);

            $video->slug   = uniqueId($video->id);
            $video->banner = $video->slug . '_banner';
            $video->save();

            $videoFile->save(auth()->id() . '/' . $video->slug . '.mp4');
            Storage::disk('video')->delete($tmpPath);
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
}
