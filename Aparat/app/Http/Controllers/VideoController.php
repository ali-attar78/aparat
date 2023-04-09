<?php

namespace App\Http\Controllers;

use App\Http\Requests\Video\ChangeStateVideoRequest;
use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\LikeVideoRequest;
use App\Http\Requests\Video\ListVideoRequest;
use App\Http\Requests\Video\RepublishVideoRequest;
use App\Http\Requests\Video\UploadBannerRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use App\Services\VideoService;

class VideoController extends Controller
{
    public function upload(UploadVideoRequest $request)
    {
        return VideoService::upload($request);
    }

    public function uploadBanner(UploadBannerRequest $request)
    {
        return VideoService::uploadBanner($request);
    }

    public function create(CreateVideoRequest $request)
    {
        return VideoService::create($request);
    }

    public function changeState(ChangeStateVideoRequest $request)
    {
        return VideoService::changeState($request);
    }

    public function list(ListVideoRequest $request)
    {
        return VideoService::list($request);
    }

    public function republish(RepublishVideoRequest $request)
    {
        return VideoService::republish($request);
    }

    public function like(LikeVideoRequest $request)
    {
        return VideoService::like($request);
    }

}
