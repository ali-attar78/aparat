<?php

namespace App\Http\Controllers;

use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Http\Requests\Channel\UploadBannerForChannelRequest;
use App\Services\ChannelService;

class ChannelController extends Controller
{
    public function update(UpdateChannelRequest $request)
    {
            return ChannelService::updateChannelInfo($request);
    }

    public function uploadBanner(UploadBannerForChannelRequest $request)
    {
         return ChannelService::uploadAvatarForChannel($request);
    }
}
