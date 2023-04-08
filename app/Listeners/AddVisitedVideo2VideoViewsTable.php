<?php

namespace App\Listeners;

use App\Events\VisitVideo;
use App\Models\VideoView;
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
            $video      = $event->getVideo();
            $conditions = [
                'user_id' => auth('api')->id(),
                'video_id'=> $video->id,
                ['created_at', '>', now()->subDays(1)]
            ];
            $ip = clientIP();
            if (!auth()->check())
            {
                $conditions['user_ip'] = $ip;
            }
            if (!VideoView::where($conditions)->count())
            {
                VideoView::create([
                    'user_id' => auth('api')->id(),
                    'video_id'=> $video->id,
                    'user_ip' => $ip
                ]);
            }

        }
        catch (\Exception $exception)
        {
            Log::error($exception);
        }
    }
}
