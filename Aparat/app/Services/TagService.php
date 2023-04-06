<?php

namespace App\Services;




 use App\Http\Requests\Playlist\ListPlaylistRequest;
 use App\Http\Requests\Playlist\MyPlaylistRequest;
 use App\Http\Requests\Playlist\PlaylistCreateRequest;
 use App\Http\Requests\Tag\CreateTagRequest;
 use App\Http\Requests\Tag\ListTagRequest;
 use App\Http\Requests\Tag\MyTagRequest;
 use App\Models\Playlist;
 use App\Models\Tag;

 class TagService extends BaseService
{


     public static function getAll(ListTagRequest $request)
     {
         return Tag::all();
     }

     public static function create(CreateTagRequest $request)
     {
         $data=$request->validated();
         return Tag::create($data);

     }
 }
