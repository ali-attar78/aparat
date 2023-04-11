<?php

namespace App\listeners;

use App\Events\VisitVideo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use PHPUnit\Exception;

class AddVisitedVideoLogToVideoViewsTable
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
    public function handle(VisitVideo $event): void
    {
        try {
            $video = $event->getVideo();
            $video->viewer()->attach(auth('api')->id());
        }
        catch (Exception $exception){
            Log::error($exception);
        }

    }
}
