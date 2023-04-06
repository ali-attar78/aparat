<?php

namespace App\Http\Controllers;

use App\Http\Requests\Playlist\ListPlaylistRequest;
use App\Http\Requests\Playlist\MyPlaylistRequest;
use App\Http\Requests\Playlist\PlaylistCreateRequest;
use App\Http\Requests\Tag\CreateTagRequest;
use App\Http\Requests\Tag\ListTagRequest;
use App\Http\Requests\Tag\MyTagRequest;
use App\Services\PlaylistService;
use App\Services\TagService;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(ListTagRequest $request)
    {
        return TagService::getAll($request);
    }


    public function create(CreateTagRequest $request)
    {
        return TagService::create($request);
    }
}
