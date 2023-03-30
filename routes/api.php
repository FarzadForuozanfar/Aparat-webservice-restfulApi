<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChannelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Auth Route API
 */
Route::group([], function($router){
    $router->group(['namespace' => 'Laravel\Passport\Http\Controllers'], function($router){
        $router->post('login', [
            'as' => 'auth.login',
            'middleware' => 'throttle',
            'uses' => 'AccessTokenController@issueToken'
        ]);
    });
    $router->post('register', [AuthController::class, 'register'])->name('auth.register');
    $router->post('register-verify', [AuthController::class, 'registerVerify'])->name('auth.register-verify');
    $router->post('resend-verification-code', [AuthController::class, 'resendVerificationCode'])->name('auth.resend-verification-code');
});

/**
 * User Route API
 */
Route::group(['middleware' => 'auth:api'], function($router){
    $router->post('change-email',
        [UserController::class, 'changeEmail'])->name('change.email');
    $router->post('change-email-submit',
        [UserController::class, 'changeEmailSubmit'])->name('change.email.submit');
});

/**
 * Channel Route API
 */
Route::group(['middleware' => 'auth:api', 'prefix' => '/channel'], function($router){
    $router->put('/{id?}',
        [ChannelController::class, 'Update'])->name('channel.update');
    $router->match(['post', 'put'],'/',
        [ChannelController::class, 'UploadAvatar'])->name('channel.upload.avatar');
    $router->match(['post', 'put'],'/socials',
        [ChannelController::class, 'UpdateSocial'])->name('channel.update.socials');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
