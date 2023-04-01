<?php

namespace App\Services;

use App\Models\Tag;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TagService extends BaseService
{

    public static function getAll(Request $request)
    {
        return Tag::all(['id', 'title']);
    }

    public static function CreateTag(Request $request)
    {
        try
        {
            $data = $request->validated();
            $tag  = Tag::create($data);
            return response(['id' => $tag->id, 'title' => $tag->title], 200);
        }
        catch (Exception $exception)
        {
            Log::error($exception);
            return response(['message' => 'خطا رخ داده است'] , 500);
        }
    }
}
