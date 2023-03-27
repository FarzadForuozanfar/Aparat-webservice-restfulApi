<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::group(['namespace' => 'Laravel\Passport\Http\Controllers',], function($router){
    $router->post('login', [
        'as' => 'auth.login',
        'middleware' => 'throttle',
        'uses' => 'AccessTokenController@issueToken'
    ]);
});

Route::group(['namespace' => 'App\Http\Controllers',], function($router){
    $router->post('register', [
        'as' => 'auth.register',
        'uses' => 'AuthController@register'
    ]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
