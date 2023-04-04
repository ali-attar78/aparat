<?php

namespace App\Services;



 use App\Http\Requests\Video\CreateVideoRequest;
 use App\Http\Requests\Video\UploadBannerRequest;
 use App\Http\Requests\Video\UploadVideoRequest;
 use App\Models\Playlist;
 use App\Models\Video;
 use FFMpeg\FFMpeg;
 use Illuminate\Support\Facades\DB;
 use Illuminate\Support\Facades\Log;
 use Illuminate\Support\Facades\Storage;
 use Illuminate\Support\Str;
 use Lcobucci\JWT\Exception;

 class VideoService extends BaseService
{


     public static function upload(UploadVideoRequest $request)
     {

         try {
             $video=$request->file('video');
             $fileName= time() . Str::random(10);
             Storage::disk('videos')->put('/tmp/' . $fileName,$video->get());

             return response([
                 'video' => $fileName
             ],200);
         }

         catch (Exception $exception){

             return response([
                 'message'=>'خطایی رخ داده است'
             ],500);
         }

     }

     public static function create(CreateVideoRequest $request)
     {
         try {


             /** @var Media $video */
             $video = \FFM::fromDisk('videos')->open('/tmp/' . $request->video_id);

             DB::beginTransaction();

             $video= Video::create([

                 'user_id' => auth()->id(),
                 'category_id' => $request->category,
                 'channel_category_id' => $request->channel_category,
                 'slug' => '',
                 'title' => $request->title,
                 'info' => $request->info,
                 'duration' => $video->getDurationInSeconds(),
                 'banner' => null,
                 'publish_at' => $request->publish_at,

             ]);

             $video->slug =uniqueId($video->id);
             $video->banner =$video->slug . '-banner';
             $video->save();


             Storage::disk('videos')->move('/tmp/' . $request->video_id, auth()->id() . '/' . $video->slug);

             if ($request->banner){
                 Storage::disk('videos')->move('/tmp/' . $request->banner, auth()->id() . '/' . $video->banner);
             }


             if ($request->playlist){
                 $playlist = Playlist::find($request->playlist);
                 $playlist->videos()->attach($video->id);
             }

             if (!empty($request->tags)){
                 $video->tags()->attach($request->tags);
             }

//             DB::rollBack();
             DB::commit();
//             $tempVideoFile = public_path('videos/tmp/' . $request->video_id);
//             $distVideoFile = public_path('videos/' . auth()->id() . '/' . $request->video_id);
             return response(['message' => 'ویدیو با موفقیت ثبت شد','data'=>$video],200);

         }
         catch (Exception $exception){
             DB::rollBack();
             Log::error($exception);
             return response(['message' => 'خطایی رخ داده است'],500);
         }
     }

     public static function uploadBanner(UploadBannerRequest $request)
     {
         try {
             $banner=$request->file('banner');
             $fileName= time() . Str::random(10) . '-banner';
             Storage::disk('videos')->put('/tmp/' . $fileName,$banner->get());


             return response([
                 'banner' => $fileName
             ],200);
         }

         catch (Exception $exception){

             return response([
                 'message'=>'خطایی رخ داده است'
             ],500);
         }

     }
 }
