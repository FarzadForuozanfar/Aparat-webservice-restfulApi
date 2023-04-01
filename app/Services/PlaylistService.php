<?php

namespace App\Services;

use App\Models\PlayList;
use Exception;
use Illuminate\Http\Request;
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

    public static function CreatePlaylist(Request $request)
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
}
