<?php

namespace App\Listeners;

use App\Events\VisitVideo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class AddVisitedVideo2VideoViewsTable
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
    public function handle(VisitVideo $event):void
    {
        try {
            $video = $event->getVideo();
            $video->viewers()->attach(auth('api')->id());
        }
        catch (\Exception $exception)
        {
            Log::error($exception);
        }
    }
}
