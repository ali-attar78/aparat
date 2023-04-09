<?php

namespace App\Services;



 use App\Events\UploadNewVideo;
 use App\Http\Requests\Video\ChangeStateVideoRequest;
 use App\Http\Requests\Video\CreateVideoRequest;
 use App\Http\Requests\Video\LikeVideoRequest;
 use App\Http\Requests\Video\ListVideoRequest;
 use App\Http\Requests\Video\RepublishVideoRequest;
 use App\Http\Requests\Video\UploadBannerRequest;
 use App\Http\Requests\Video\UploadVideoRequest;
 use App\Jobs\ConvertAndAddWaterMarkToUploadedVideoJob;
 use App\Models\Playlist;
 use App\Models\Video;
 use App\Models\VideoFavourite;
 use App\Models\VideoRepublish;
 use FFMpeg\FFMpeg;
 use FFMpeg\Filters\Video\CustomFilter;
 use FFMpeg\Filters\Video\VideoFilters;
 use http\Env\Response;
 use Illuminate\Database\Eloquent\ModelNotFoundException;
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


             DB::beginTransaction();

             $video= Video::create([

                 'user_id' => auth()->id(),
                 'category_id' => $request->category,
                 'channel_category_id' => $request->channel_category,
                 'slug' => '',
                 'title' => $request->title,
                 'info' => $request->info,
                 'duration' => 0,
                 'banner' => null,
                 'enable_comments' => $request->enable_comments,
                 'publish_at' => $request->publish_at,
                 'state' => Video::STATE_PENDING,

             ]);

             $video->slug =uniqueId($video->id);
             $video->banner =$video->slug . '-banner';
             $video->save();


             event(new UploadNewVideo($video,$request));
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

     public static function changeState(ChangeStateVideoRequest $request)
     {
         $video=$request->video;
         $video->state=$request->state;
         $video->save();

         return response($video);
     }

     public static function list(ListVideoRequest $request)
     {
         $user=auth()->user();

         if ($request->has('republished'))
         {
         $videos = $request->republished ? $user->republishedVideos() : $user->channelVideos();
         }
         else{
             $videos=$user->videos();
         }

         $result = $videos->orderBy('id')->paginate(10);
         return $result;
     }

     public static function republish(RepublishVideoRequest $request)
     {
         try {


             VideoRepublish::create([
                 'user_id' =>  auth()->id,
                 'video_id' => $request->video->id,
             ]);
             return response(['message' => 'republish successfully'], 200);

         }
         catch (Exception $exception)
         {
             Log::error($exception);
             return response(['message' => 'republish failed'], 500);

         }

     }

     public static function like(LikeVideoRequest $request)
     {
         $user=auth()->user();
         $video = $request->video;
         $like=$request->like;
         $favourites=$user->favouriteVideos()->where(['video_id'=>$video->id])->first();


         if (empty($favourites)){
             if ($like)
             {
                 VideoFavourite::create([
                    'user_id'=>$user->id,
                    'video_id' => $video->id,
                 ]);
             }
             else{
                 return response(['message'=>'شما قادر به این کار نیستید'],400);
             }
         }
         else{

             if (!$like)
             {
                 VideoFavourite::where([
                     'user_id'=>$user->id,
                     'video_id' => $video->id,
                 ])->delete();
             }
             else{
                 return response(['message'=>'شما قبلا این ویدیو زا پسند کرده اید'],400);
             }

         }

         return response(['message'=>'با موفقیت ثبت شد'],200);


     }


 }
