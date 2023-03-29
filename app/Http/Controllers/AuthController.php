<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterNewUserRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\Http\Requests\Auth\ResendVerificationCodeRequest;
use App\Services\UserService;

class AuthController extends Controller
{
    public function register(RegisterNewUserRequest $request)
    {
        return UserService::registerNewUser($request);
    }

    public function registerVerify(RegisterVerifyUserRequest $request)
    {
        return UserService::registerNewUserVerify($request);
    }

    public function resendVerificationCode(ResendVerificationCodeRequest $request)
    {
        return UserService::resendVerificationCode2User($request);
    }
}
