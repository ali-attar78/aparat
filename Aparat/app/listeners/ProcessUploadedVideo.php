<?php

namespace App\listeners;

use App\Events\UploadNewVideo;
use App\Jobs\ConvertAndAddWaterMarkToUploadedVideoJob;
use FFMpeg\Filters\Video\CustomFilter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use ProtoneMedia\LaravelFFMpeg\Filesystem\Media;

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

        ConvertAndAddWaterMarkToUploadedVideoJob::dispatch($event->getVideo(),$event->getRequest()->video_id,$event->getRequest()->enable_watermark);

    }
}
