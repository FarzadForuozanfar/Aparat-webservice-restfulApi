<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Channel;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CategoryService extends BaseService
{

    public static function getAll(Request $request)
    {
        return Category::all();
    }

    public static function getMyCategory(Request $request)
    {
        return Category::where('user_id', auth()->id())->get();
    }

    public static function CreateCategory(Request $request)
    {
        return $request->all();
    }

    public static function UploadBanner(Request $request)
    {
        dd($request->all());
    }
}
