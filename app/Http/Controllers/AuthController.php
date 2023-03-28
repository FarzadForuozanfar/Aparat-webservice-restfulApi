<?php

namespace App\Http\Controllers;

use App\Exceptions\RegisterVerificationException;
use App\Exceptions\UserAlreadyRegisteredExcemption;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\AuthregisterNewUserRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest ;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function register(AuthregisterNewUserRequest $request)
    {
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
        $code  = rand(100000,900000);
        $user = User::create([
            $field => $value,
            'verified_code' => $code
        ]);

        return response(['message' => 'کاربر موقت ثبت شد'], 200);
    }

    public function registerVerify(RegisterVerifyUserRequest $request)
    {
        $field = $request->has('email') ? 'email' : 'mobile';
        $code = $request->code;
        $user = User::where(['verified_code', $code, $field => $request->input($field)])->first();
        if (empty($user))
        {
            throw new ModelNotFoundException('کاربری با کد مورد نظر یافت نشد');
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
}
