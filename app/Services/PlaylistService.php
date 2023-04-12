<?php

namespace App\Services;

use App\Models\PlayList;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlaylistService extends BaseService
{

    public static function getAll(Request $request)
    {
        return PlayList::all();
    }

    public static function getMyPlaylist(Request $request)
    {
        return auth()->user()->playlist;
    }

    public static function createPlaylist(Request $request)
    {
        try
        {
            $data     = $request->validated();
            $user     = auth()->user();
            $playlist =  $user->playlist()->create($data);
            return response(['data' => $playlist], 200);
        }
        catch (Exception $exception)
        {
            Log::error($exception);
            return response(['message' => 'خطا رخ داده است'] , 500);
        }
    }

    public static function attachVideo(Request $request)
    {
        try {
            DB::table('playlists_videos')->where(['video_id' => $request->video->id])->delete();
            $request->playlist->videos()->syncWithoutDetaching($request->video->id);
            return response(['message' => 'ویدیو با موفقی به لیست پخش اضافه شد'],200);
        }
        catch (Exception $exception)
        {
            Log::error($exception);
            return response(['message' => $exception->getMessage()], 500);
        }
    }

    public static function sortVideo(Request $request)
    {
        try {
            $request->playlist->videos()->detach($request->videos);
            $request->playlist->videos()->attach($request->videos);
            return response(['message' => 'لیست پخش با موفقیت مرتب سازی شد'], 200);
        }
        catch (Exception $exception)
        {
            Log::error($exception, $request->videos);
            return response(['message' => $exception->getMessage()], 500);
        }
    }

    public static function showVideos(Request $request)
    {
        return PlayList::with('videos')->find($request->playlist->id);
    }
}
