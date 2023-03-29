<?php

namespace App\Services;

use App\Exceptions\RegisterVerificationException;
use App\Exceptions\UserAlreadyRegisteredExcemption;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserService extends BaseService
{
    const CHANGE_EMAIL_CACHE_KEY = 'change.email';

    public static function registerNewUser(Request $request): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try
        {
            DB::beginTransaction();
            $field = $request->getFieldName();
            $value = $request->getFieldValue();

            // $key   = "user-auth-register-" . $value;
            // $expiration = config('auth.register_cache_expiration', 1);
            // Cache::put($key, compact('code', 'field'), now()->addDays($expiration));
            // dd(Cache::get($key), $key, $expiration, $code);
            $user = User::where($field , $value)->first();
            if ($user)
            {
                if ($user->verified_at)
                {
                    throw new UserAlreadyRegisteredExcemption('شما قبلا ثبت نام کرده اید');
                }
                return response(['message' => 'کد فعالسازی برای شما قبلا ارسال شده است'], 200);
            }
            $code  = createVerifyCode();
            $user  = User::create([
                $field => $value,
                'verified_code' => $code
            ]);


            DB::commit();
            return response(['message' => 'کاربر موقت ثبت شد'], 200);
        }
        catch (\Exception $ex)
        {
            DB::rollBack();
            if ($ex instanceof UserAlreadyRegisteredExcemption)
            {
                throw $ex;
            }
            Log::error($ex);
            return response(['message' => 'خطایی رخ داده است'], 400);
        }
    }

    public static function registerNewUserVerify(Request $request): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $field = $request->has('email') ? 'email' : 'mobile';
        $code = $request->code;
        $user = User::where(['verified_code' => $code, $field => $request->input($field)])->first();
        if (empty($user))
        {
            throw new ModelNotFoundException('کاربری با اطلاعات مورد نظر یافت نشد');
        }
        $user->verified_code = null;
        $user->verified_at = now();
        $user->save();
        return response($user, 200);
        // $key   = "user-auth-register-" . $field;
        // $registerData = Cache::get($key);

        // if (!empty($registerData) && $registerData['code'] == $code)
        // {

        // }

        throw new RegisterVerificationException('کد تاییدیه وارد شده اشتباه می باشد');
    }

    public static function resendVerificationCode2User(Request $request): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $field   = $request->getFieldName();
        $value   = $request->getFieldValue();
        $user    = User::where([$field => $value, 'verified_at' => null])->first();

        if (!empty($user))
        {
            $diffMin = now()->diffInMinutes($user->updated_at);
            if ($diffMin > config('auth.resend_verification_code_time', 60))
            {
                $user->verified_code = createVerifyCode();
                $user->save();
            }

            return response(['message' => 'کد فعالسازی مجددا برای شما ارسال گردید'], 200);
        }

        throw new ModelNotFoundException('کاربری با این مشخصات یافت نشد و یا قبلا فعالسازی شده است');
    }

    public static function changeEmailUser(Request $request)
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

    public static function changeEmailSubmitUser(Request $request)
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
