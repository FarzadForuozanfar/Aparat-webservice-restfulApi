<?php

namespace App\Jobs;

use App\Models\Video;
use FFMpeg\Filters\Video\CustomFilter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Filesystem\Media;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class AddFilter2UploadedVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Video $video;
    private string|int $video_id;
    private $user;
    private bool $enable_watermark;

    /**
     * Create a new job instance.
     * @param Video $video
     * @param $video_id
     * @param $enable_watermark
     */
    public function __construct(Video $video, $video_id, bool $enable_watermark)
    {

        $this->video    = $video;
        $this->video_id = $video_id;
        $this->user     = auth()->user();
        $this->enable_watermark = $enable_watermark;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            /** @var Media $videoFile */
            $tmpPath       = '/tmp/' . $this->video_id;
            $channelName   = $this->user->channel->name;
            $videoFile     = FFMpeg::fromDisk('video')->open($tmpPath);
            $format        = new \FFMpeg\Format\Video\X264('libmp3lame');
            if ($this->enable_watermark)
            {
                $filter = new CustomFilter("drawtext=text='aparat.me/{$channelName}': fontcolor=blue: fontsize=24:
                          box=1: boxcolor=white@0.4: boxborderw=5:
                          x=10: y=(h - text_h - 10)");
                $videoFile = $videoFile->addFilter($filter);
            }
            $videoFilter = $videoFile->export()->toDisk('video')->inFormat($format);
            $videoFilter->save($this->user->id . '/' . $this->video->slug . '.mp4');
            Storage::disk('video')->delete($tmpPath);
            $this->video->duration = $videoFile->getDurationInSeconds();
            $this->video->state = Video::CONVERTED;
            $this->video->save();
        }
        catch (\Exception $exception)
        {
            Log::error($exception);
        }
    }

    /**
     * @return Video
     */
    public function getVideo(): Video
    {
        return $this->video;
    }

}
