<?php

namespace App\Services;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryService extends BaseService
{

    public static function getAll(Request $request)
    {
        return Category::all();
    }

    public static function getMyCategory(Request $request)
    {
        return auth()->user()->categories;
    }

    public static function CreateCategory(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $data     = $request->validated();
            $user     = auth()->user();
            if ($request->banner)
            {
                Storage::disk('category')->move('/tmp/' . $request->banner, auth()->id() . '/' . $request->banner);
            }
            $category = $user->categories()->create($data);
            DB::commit();
            return response(['data' => $category], 200);
        }
        catch (Exception $ex)
        {
            if (Storage::disk('category')->exists(auth()->id() . '/' . $request->banner))
            {
                Storage::disk('category')->delete(auth()->id() . '/' . $request->banner);
            }
            DB::rollBack();
            Log::error($ex);
            return response(['message' => 'خطا رخ داده است' . $ex->getMessage()], 500);
        }
    }

    public static function UploadBanner(Request $request)
    {
        try
        {
            $banner   = $request->file('banner');
            $fileName = time() . Str::random(24) . '_banner';
            Storage::disk('category')->put('/tmp/' . $fileName, $banner->get());
            return response(['banner' => $fileName], 200);
        }
        catch (Exception $exception)
        {
            Log::error($exception);
            return response(['message' => $exception->getMessage()], 500);
        }
    }
}
