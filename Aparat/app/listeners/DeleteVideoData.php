<?php

namespace App\listeners;

use App\Events\DeleteVideo;
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
    public function handle(DeleteVideo $event): void
    {
        $video = $event->getVideo();

        Storage::disk('videos')
            ->delete(auth()->id() . '/' . $video->banner);

        Storage::disk('videos')
            ->delete(auth()->id() . '/' . $video->slug . 'mp4');
    }
}
