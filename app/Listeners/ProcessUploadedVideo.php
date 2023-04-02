<?php

namespace App\Listeners;

use App\Events\UploadNewVideo;
use App\Jobs\AddFilter2UploadedVideoJob;

class ProcessUploadedVideo
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UploadNewVideo $event): void
    {
        AddFilter2UploadedVideoJob::dispatch($event->getVideo(), $event->getRequest()->video_id);
    }
}
