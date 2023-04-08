<?php

namespace App\Jobs;

use App\Models\Video;
use FFMpeg\Filters\Video\CustomFilter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Filesystem\Media;

class ConvertAndAddWaterMarkToUploadedVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Video $video;
    private string $videoId;
    private  $userId;
    private bool $addWatermark;


    /**
     * Create a new job instance.
     */
    public function __construct(Video $video,string $videoId,bool $addWatermark)
    {

        $this->video = $video;
        $this->videoId = $videoId;
        $this->userId=auth()->id();
        $this->addWatermark = $addWatermark;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {


        $upladedVideoPath='/tmp/' . $this->videoId;
        $videoUploaded = \FFM::fromDisk('videos')->open($upladedVideoPath);


        $format = new \FFMpeg\Format\Video\X264('libmp3lame');

        /** @var Media $videoFile */

        if ($this->addWatermark){
            $filter=new CustomFilter("drawtext=text='http\\://aliattar.com'
             :fontcolor=white:fontsize=30:box=1:boxcolor=white@0.5");

            $videoUploaded = $videoUploaded->addFilter($filter);
        }

        /** @var Media $videoFile */
        $videoFile = $videoUploaded->export()
            ->toDisk('videos')
            ->inFormat($format);

        $videoFile->save($this->userId . '/' . $this->video->slug . '.mp4');

        $this->video->duration =$videoUploaded->getDurationInSeconds();
        $this->video->state = Video::STATE_CONVERTED;
        $this->video->save();

        Storage::disk('videos')->delete($upladedVideoPath);
    }
}
