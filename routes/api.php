<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
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
Route::group(['middleware' => 'auth:api'], function($router){ //TODO add user prefix & fix it postman
    $router->post('change-email', [UserController::class, 'changeEmail'])->name('change.email');

    $router->post('change-email-submit', [UserController::class, 'changeEmailSubmit'])->name('change.email.submit');

    $router->match(['post', 'put'],'change-password', [UserController::class, 'changePassword'])->name('password.change');

    $router->get('/followings', [UserController::class, 'followingsList'])->name('followings.list');

    $router->get('/followers', [UserController::class, 'followersList'])->name('followers.list');

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

    $router->match(['post', 'get'],'/{channel}/follow',
        [ChannelController::class, 'Follow'])->name('channel.follow');

    $router->match(['post', 'get'],'/{channel}/unfollow',
        [ChannelController::class, 'UnFollow'])->name('channel.unfollow');

    $router->get('/statistics', [ChannelController::class, 'Statistics'])->name('channel.statistics');
});

/**
 * Video Route API
 */
Route::group(['middleware' => 'auth:api', 'prefix' => '/video'], function($router){
    $router->post('/upload', [VideoController::class, 'Upload'])->name('video.upload');

    $router->post('/upload-banner', [VideoController::class, 'UploadBanner'])->name('video.upload.banner');

    $router->post('/', [VideoController::class, 'Create'])->name('video.create');

    $router->put('/{video}/state', [VideoController::class, 'ChangeState'])->name('video.change.state');

    $router->get('/', [VideoController::class, 'Index'])->name('video.list')->withoutMiddleware(['auth:api']);

    $router->post('/{video}/republish', [VideoController::class, 'Republish'])->name('video.republish');

    $router->match(['post', 'get'], '/{video}/like', [VideoController::class, 'Like'])->name('video.like')->withoutMiddleware(['auth:api']);

    $router->match(['post', 'get'], '/{video}/unlike', [VideoController::class, 'UnLike'])->name('video.unlike')->withoutMiddleware(['auth:api']);

    $router->get('/liked', [VideoController::class, 'LikedByCurrentUser'])->name('video.liked.list');

    $router->get('/{video}', [VideoController::class, 'Show'])->name('video.show')->withoutMiddleware(['auth:api']);;

});

/**
 * Category Route API
 */
Route::group(['middleware' => 'auth:api', 'prefix' => '/category'], function($router){

    $router->post('/',
        [CategoryController::class, 'Create'])->name('category.create');

    $router->post('/upload-banner',
        [CategoryController::class, 'UploadBanner'])->name('category.upload.banner');

    $router->get('/',
        [CategoryController::class, 'Index'])->name('category.all');

    $router->get('/my',
        [CategoryController::class, 'My'])->name('category.my');
});

/**
 * Playlist Route API
 */
Route::group(['middleware' => 'auth:api', 'prefix' => '/playlist'], function($router){

    $router->post('/',
        [PlaylistController::class, 'Create'])->name('playlist.create');

    $router->get('/',
        [PlaylistController::class, 'Index'])->name('playlist.all');

    $router->get('/my',
        [PlaylistController::class, 'My'])->name('playlist.my');
});

/**
 * Tags Route API
 */
Route::group(['middleware' => 'auth:api', 'prefix' => '/tags'], function($router){
    $router->post('/',
        [TagController::class, 'Create'])->name('tag.create');

    $router->get('/',
        [TagController::class, 'Index'])->name('tag.all');
});

/**
 * Comments Route API
 */
Route::group(['middleware' => 'auth:api', 'prefix' => '/comments'], function($router){
    $router->post('/',
        [CommentController::class, 'Create'])->name('comment.create');

    $router->get('/',
        [CommentController::class, 'Index'])->name('comment.all');
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
