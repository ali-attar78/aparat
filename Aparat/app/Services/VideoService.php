<?php

namespace App\Services;



 use App\Events\DeleteVideo;
 use App\Events\UploadNewVideo;
 use App\Events\VisitVideo;
 use App\Http\Requests\Video\ChangeStateVideoRequest;
 use App\Http\Requests\Video\FavouriteVideoListRequest;
 use App\Http\Requests\Video\ShowCommentVideoRequest;
 use App\Http\Requests\Video\CreateVideoRequest;
 use App\Http\Requests\Video\DeleteVideoRequest;
 use App\Http\Requests\Video\LikedByCurrentUserVideoRequest;
 use App\Http\Requests\Video\LikeVideoRequest;
 use App\Http\Requests\Video\ListVideoRequest;
 use App\Http\Requests\Video\RepublishVideoRequest;
 use App\Http\Requests\Video\ShowVideoRequest;
 use App\Http\Requests\Video\StatisticsVideoRequest;
 use App\Http\Requests\Video\UnLikeVideoRequest;
 use App\Http\Requests\Video\UpdateVideoRequest;
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
 use http\Client\Curl\User;
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
             $video = $request->file('video');
             $fileName = time() . Str::random(10);
             Storage::disk('videos')->put('/tmp/' . $fileName, $video->get());

             return response([
                 'video' => $fileName
             ], 200);
         } catch (Exception $exception) {

             return response([
                 'message' => 'خطایی رخ داده است'
             ], 500);
         }

     }

     public static function create(CreateVideoRequest $request)
     {
         try {


             DB::beginTransaction();

             $video = Video::create([

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

             $video->slug = uniqueId($video->id);
             $video->banner = $video->slug . '-banner';
             $video->save();


             event(new UploadNewVideo($video, $request));
             if ($request->banner) {
                 Storage::disk('videos')->move('/tmp/' . $request->banner, auth()->id() . '/' . $video->banner);
             }


             if ($request->playlist) {
                 $playlist = Playlist::find($request->playlist);
                 $playlist->videos()->attach($video->id);
             }

             if (!empty($request->tags)) {
                 $video->tags()->attach($request->tags);
             }

//             DB::rollBack();
             DB::commit();
//             $tempVideoFile = public_path('videos/tmp/' . $request->video_id);
//             $distVideoFile = public_path('videos/' . auth()->id() . '/' . $request->video_id);
             return response(['message' => 'ویدیو با موفقیت ثبت شد', 'data' => $video], 200);

         } catch (Exception $exception) {
             DB::rollBack();
             Log::error($exception);
             return response(['message' => 'خطایی رخ داده است'], 500);
         }
     }

     public static function uploadBanner(UploadBannerRequest $request)
     {
         try {
             $banner = $request->file('banner');
             $fileName = time() . Str::random(10) . '-banner';
             Storage::disk('videos')->put('/tmp/' . $fileName, $banner->get());


             return response([
                 'banner' => $fileName
             ], 200);
         } catch (Exception $exception) {

             return response([
                 'message' => 'خطایی رخ داده است'
             ], 500);
         }

     }

     public static function changeState(ChangeStateVideoRequest $request)
     {
         $video = $request->video;
         $video->state = $request->state;
         $video->save();

         return response($video);
     }

     public static function list(ListVideoRequest $request)
     {
         $user = auth('api')->user();

         if ($request->has('republished')) {
             if ($user) {
                 $videos = $request->republished ? $user->republishedVideos() : $user->channelVideos();
             } else {
                 $videos = $request->republished ? Video::whereRepublished() : Video::whereNotRepublished();
             }
         } else {
             $videos = $user ? $user->videos() : Video::query();
         }


         $result = $videos->orderBy('id')->paginate();
         return $result;
     }

     public static function republish(RepublishVideoRequest $request)
     {
         try {


             VideoRepublish::create([
                 'user_id' => auth()->id,
                 'video_id' => $request->video->id,
             ]);
             return response(['message' => 'republish successfully'], 200);

         } catch (Exception $exception) {
             Log::error($exception);
             return response(['message' => 'republish failed'], 500);

         }

     }

     public static function like(LikeVideoRequest $request)
     {

         VideoFavourite::create([
             'user_id' => auth('api')->id(),
             'user_ip' => client_ip(),
             'video_id' => $request->video->id,
         ]);

         return response(['message' => 'با موفقیت ثبت شد'], 200);


     }

     public static function likedByCurrentUser(LikedByCurrentUserVideoRequest $request)
     {
         $user = $request->user();
         $videos = $user->favouriteVideos()->paginate();

         return $videos;
     }

     public static function unlike(UnLikeVideoRequest $request)
     {
         $user = auth('api')->user();

         $conditions = [
             'video_id' => $request->video->id,
             'user_id' => $user ? $user->id : null,
         ];

         if (empty($user)) {
             $conditions['user_ip'] = client_ip();
         }

         VideoFavourite::where($conditions)->delete();

         return response(['message' => 'با موفقیت ثبت شد'], 200);


     }

     public static function show(ShowVideoRequest $request)
     {
         event(new VisitVideo($request->video));

         $videoData = $request->video->toArray();

         $conditions=[
             'video_id'=> $request->video->id,
             'user_id'=>auth('api')->check() ? auth('api')->id() : null,

         ];

         if (!auth('api')->check()){
             $conditions['user_ip'] = client_ip();
         }

         $videoData['liked'] = VideoFavourite::where($conditions)->count();
         $videoData['tags'] = $request->video->tags;
         $videoData['comments'] = sort_comments($request->video->comments,null);

         $videoData['related_videos'] =$request->video->related()->take(5)->get();

         $videoData['playlist'] =$request->video->playlist()
             ->with('videos')
             ->first();


         return $videoData;
     }

     public static function delete(DeleteVideoRequest $request)
     {
         try {
             DB::beginTransaction();
             $request->video->forceDelete();
             event(new DeleteVideo($request->video));
             DB::commit();
             return response(['message' => 'با موفقیت حذف شد'], 200);
         } catch (Exception $exception) {
             DB::rollBack();
             Log::error($exception);
             return response(['message' => 'حذف انجام نشد'], 500);

         }

     }

     public static function statistics(StatisticsVideoRequest $request)
     {

         $fromDate = now()->subDays($request->get('last_n_date',7))->toDateString();

         $data = [
             'views' => [],
             'total_views' => 0,
         ];

         Video::views($request->user()->id)
             ->where('videos.id', $request->video->id)
             ->whereRaw("date(video_views.created_at) >= '{$fromDate}'")
             ->selectRaw('date(video_views.created_at) as date, count(*) as views')
             ->groupBy(DB::raw('date(video_views.created_at)'))
             ->get()
             ->each(function ($item) use (&$data) {

                 $data['total_views'] += $item->views;
                 $data['views'][$item->date] = $item->views;

             });

         return $data;


     }

     public static function update(UpdateVideoRequest $request)
     {
            $video=$request->video;
         try {
             DB::beginTransaction();

             if ($request->has('title')) $video->title = $request->title;
             if ($request->has('info')) $video->info = $request->info;
             if ($request->has('category')) $video->category_id = $request->category;
             if ($request->has('channelCategory')) $video->channel_category_id = $request->channel_category;
             if ($request->has('enable_comments')) $video->enable_comments = $request->enable_comments;

             if ($request->banner) {
                 Storage::disk('videos')
                     ->delete(auth()->id() . '/' . $video->banner);
                 Storage::disk('videos')
                     ->move('/tmp/' . $request->banner, auth()->id() . '/' . $video->banner);
             }

             if (!empty($request->tags)) {
                 $video->tags()->sync($request->tags);
             }

             DB::commit();

             return response(['message' => 'ویدیو با موفقیت تغییر یافت', 'data' => $video], 200);

         } catch (Exception $exception) {
             DB::rollBack();
             Log::error($exception);
             return response(['message' => 'خطایی رخ داده است'], 500);
         }



     }

     public static function favourites(FavouriteVideoListRequest $request)
     {

         $videos = $request->user()
             ->favouriteVideos()
             ->selectRaw('videos.*,channels.name channel_name')
             ->leftJoin('channels','channels.user_id','=' , 'videos.user_id')
             ->get();

         return[
           'videos'=>$videos,
           'total_fav_videos' => count($videos),
           'total_videos' => $request->user()->channelVideos()->count(),
           'total_comments' => Video::channelComments($request->user()->id)
             ->selectRaw('comments.*')
             ->count(),
             'total_views'=>Video::views($request->user()->id)->count()

         ];


     }


 }
