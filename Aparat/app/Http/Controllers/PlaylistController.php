<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\ListCategoryRequest;
use App\Http\Requests\Category\UploadCategoryBannerRequest;
use App\Http\Requests\Playlist\AddVideoToPlaylistRequest;
use App\Http\Requests\Playlist\ListPlaylistRequest;
use App\Http\Requests\Playlist\MyPlaylistRequest;
use App\Http\Requests\Playlist\PlaylistCreateRequest;
use App\Http\Requests\Playlist\ShowPlaylistRequest;
use App\Http\Requests\Playlist\SortVideoInPlaylistRequest;
use App\Services\CategoryService;
use App\Services\PlaylistService;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    public function index(ListPlaylistRequest $request)
    {
        return PlaylistService::getAll($request);
    }

    public function my(MyPlaylistRequest $request)
    {
        return PlaylistService::my($request);
    }


    public function create(PlaylistCreateRequest $request)
    {
        return PlaylistService::create($request);
    }

   public function addVideo(AddVideoToPlaylistRequest $request)
    {
        return PlaylistService::addVideo($request);
    }

   public function sortVideos(SortVideoInPlaylistRequest $request)
    {
        return PlaylistService::sortVideos($request);
    }

    public function show(ShowPlaylistRequest $request)
    {
        return PlaylistService::show($request);
    }


}
