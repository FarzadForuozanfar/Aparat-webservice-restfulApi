<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('aparat:clear', function () {
    clear_storage('video');
    $this->info('CLear uploaded video files');

    clear_storage('category');
    $this->info('CLear uploaded category files');

    clear_storage('channel');
    $this->info('CLear uploaded channel files');

})->describe('Clear all temporary files,...');
