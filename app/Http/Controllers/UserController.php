<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ChangeEmailSubmitRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Services\UserService;

class UserController extends Controller
{

    public function  changeEmail(ChangeEmailRequest $request)
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
}
