<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\unRegisterUserRequest;
use App\Http\Requests\User\ChangeEmailSubmitRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Services\UserService;

class UserController extends Controller
{

    public function  changeEmail(unRegisterUserRequest $request)
    {
        return UserService::changeEmailUser($request);
    }

    public function changeEmailSubmit(ChangeEmailSubmitRequest $request)
    {
        return UserService::changeEmailSubmitUser($request);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        return UserService::changePasswordUser($request);
    }

    public function followingsList(unRegisterUserRequest $request)
    {
        return UserService::followingsList($request);
    }

    public function followersList(unRegisterUserRequest $request)
    {
        return UserService::followersList($request);
    }

    public function unRegister(unRegisterUserRequest $request)
    {
        return UserService::unRegisterUser($request);
    }
}
