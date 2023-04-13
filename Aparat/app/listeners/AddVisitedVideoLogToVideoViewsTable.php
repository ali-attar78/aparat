<?php

namespace App\listeners;

use App\Events\VisitVideo;
use App\Models\VideoView;
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

            $conditions = [
              'user_id'=>auth('api')->id(),
              'video_id'=> $video->id,
                ['created_at','>',now()->subDays(1)]
            ];

            $clientIp=client_ip();

            if (!auth('api')->check())
            {
                $conditions['user_ip']=$clientIp;
            }

            if (!VideoView::where($conditions)->count()){
                VideoView::create([
                    'user_id'=>auth('api')->id(),
                    'video_id'=> $video->id,
                    'user_ip'=>$clientIp,
                ]);
            }

        }
        catch (Exception $exception){
            Log::error($exception);
        }

    }
}
