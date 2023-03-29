<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ChangeEmailSubmitRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;

class UserController extends Controller
{
    const CHANGE_EMAIL_CACHE_KEY = 'change.email';
    /**
     * @param ChangeEmailRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function  changeEmail(ChangeEmailRequest $request)
    {
        try
        {
            $email      = $request->email;
            $userId     = auth()->id();
            $code       = createVerifyCode();
            $key        = self::CHANGE_EMAIL_CACHE_KEY . $userId;
            $expireDate = now()->addMinutes(config('auth.change_email_cache_expiration'), 1440);
            Cache::put($key, compact('email', 'code') , $expireDate);
            Log::info('SEND-CHANGE-EMAIL-CODE', compact('email', 'userId', 'code'));
            return response([
                'message' => 'کد فعالسازی با موفقیت ارسال شد'
            ], 200);
        }
        catch (\Exception $ex)
        {
            Log::error($ex);
            return response([
                'message' => 'خطایی رخ داده است و سرور قادر به ارسال کد فعالسازی نمی باشد'
            ], 500);
        }
    }

    public function changeEmailSubmit(ChangeEmailSubmitRequest $request)
    {
        $userId    = auth()->id();
        $cache_key = self::CHANGE_EMAIL_CACHE_KEY . $userId;
        $cache     = Cache::get($cache_key);
        if (empty($cache) || (string)$cache['code'] != $request->code)
        {
            return response([
                'message' => 'درخواست نامعتبر است'
            ], 400);
        }
        $user        = auth()->user();
        $user->email = $cache['email'];
        $user->save();
        Cache::forget($cache_key);
        return response([
            'message' => 'ایمیل با موفقیت تغییر یافت'
        ], 200);
    }
}
