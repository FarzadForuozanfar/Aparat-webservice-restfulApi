<?php

namespace App\Listeners;

use App\Events\UploadNewVideo;
use App\Jobs\AddFilter2UploadedVideoJob;
use Illuminate\Support\Facades\Log;

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
        Log::info(date('Y-m-d H:i:s'), [$event->getVideo(), $event->getRequest()->video_id, $event->getRequest()->enable_watermark]);
        AddFilter2UploadedVideoJob::dispatch($event->getVideo(), $event->getRequest()->video_id, $event->getRequest()->enable_watermark);
    }
}
