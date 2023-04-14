<?php

namespace App\Services;




 use App\Http\Requests\Playlist\AddVideoToPlaylistRequest;
 use App\Http\Requests\Playlist\ListPlaylistRequest;
 use App\Http\Requests\Playlist\MyPlaylistRequest;
 use App\Http\Requests\Playlist\PlaylistCreateRequest;
 use App\Models\Playlist;
 use Illuminate\Support\Facades\DB;

 class PlaylistService extends BaseService
{


     public static function getAll(ListPlaylistRequest $request)
     {
         return Playlist::all();
     }

     public static function my(MyPlaylistRequest $request)
     {
         return Playlist::where('user_id',auth()->id())->get();
     }

     public static function create(PlaylistCreateRequest $request)
     {
         $data=$request->validated();
         $user=auth()->user();
         $playlist = $user->playlists()->create($data);
         return response($playlist,200);


     }

     public static function addVideo(AddVideoToPlaylistRequest $request)
     {
         DB::table('playlist_videos')
             ->where('video_id',$request->video->id)
             ->delete();

         $request->playlist
             ->videos()
             ->attach($request->video->id);

         return response(['message' => 'ویدیو با موفقیت به لیست پخش اضافه شد'],200);

     }
 }
