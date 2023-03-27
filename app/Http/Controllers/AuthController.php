<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Auth\AuthregisterNewUserRequest;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function register(AuthregisterNewUserRequest $request)
    {
        $field = $request->has('email') ? 'email' : 'mobile';
        $value = $request->input($field);
        $key   = "user-auth-register-" . $value;
        Cache::put($key, $field, now()->addDays(5));
        dd(Cache::get($key), $key);
        return response(['message' => 'کاربر موقت ثبت شد'], 200);
    }
}
