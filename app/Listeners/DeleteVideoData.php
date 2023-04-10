<?php

namespace App\Listeners;

use App\Events\DeleteVideoEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;

class DeleteVideoData
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
    public function handle(DeleteVideoEvent $event): void
    {
        $video = $event->getVideo();
        Storage::disk('video')
            ->delete(auth()->id() . '/' . $video->banner);

        Storage::disk('video')
            ->delete(auth()->id() . '/' . $video->slug . '.mp4');
    }
}
