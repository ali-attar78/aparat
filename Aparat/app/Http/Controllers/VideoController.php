<?php

namespace App\Http\Controllers;

use App\Http\Requests\Video\UploadVideoRequest;
use App\Services\VideoService;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function upload(UploadVideoRequest $request)
    {
        return VideoService::upload($request);
    }
}
