<?php

namespace App\Services;

 use App\Http\Requests\Channel\StatisticsRequest;
 use App\Http\Requests\Channel\UpdateChannelRequest;
 use App\Http\Requests\Channel\UpdateSocialsRequest;
 use App\Http\Requests\Channel\UploadBannerForChannelRequest;
 use App\Models\Channel;
 use App\Models\Video;
 use Illuminate\Support\Facades\DB;
 use Illuminate\Support\Facades\Log;
 use Illuminate\Support\Facades\Storage;
 use Illuminate\Support\Str;
 use Lcobucci\JWT\Exception;

 class ChannelService extends BaseService
{


     public static function updateChannelInfo(UpdateChannelRequest $request)
     {

         try {

             if ($channelId = $request->route('id'))
             {
                 $channel = Channel::findOrFail($channelId);
                 $user = $channel->user;
             }
             else{
                 $user = auth()->user();
                 $channel= $user->channel;
             }

             DB::beginTransaction();

             $channel->name=$request->name;
             $channel->info=$request->info;
             $channel->save();

             $user->website = $request->website;
             $user->save();

             DB::commit();
             return response([
                 "message"=>'ثبت تغییرات کانال انجام شد'
             ],200);

         }
         catch (Exception $exception){
             DB::rollBack();

             Log::error($exception);
             return response([
                 "message"=>'خطایی رخ داده است'
             ],500);

         }



     }

     public static function uploadAvatarForChannel(UploadBannerForChannelRequest $request)
     {

         try {
             $banner=$request->file('banner');
             $fileName= md5(auth()->id()) . '-' . Str::random(15);
             Storage::disk('channel')->put($fileName,$banner->get());

             $channel=auth()->user()->channel;
             if ($channel->banner){
                 Storage::disk('channel')->delete($channel->banner);
             }
             $channel->banner=Storage::disk('channel')->path($fileName);
             $channel->save();

             return response([
                 'banner' => Storage::disk('channel')->url($fileName)
             ],200);
         }

         catch (Exception $exception){

             return response([
                 'message'=>'خطایی رخ داده است'
             ],500);
         }
     }

     public static function updateSocials(UpdateSocialsRequest $request)
     {
         try {

             $socials = [

                 'cloob' => $request->input('cloob'),
                 'lenzo' => $request->input('lenzo'),
                 'facebook' => $request->input('facebook'),
                 'twitter' => $request->input('twitter'),
                 'telegram' => $request->input('telegram')

             ];

             $channel = auth()->user()->channel;
             $channel->update(['socials' => json_encode($socials)]);

             return response(["message" => "با موفقیت انجام شد"], 200);

         }
         catch (Exception $exception){
             Log::error($exception);
             return response(["message" => "خطایی رخ داده است"], 500);

         }
     }

     public static function statistics(StatisticsRequest $request)
     {


         $data = [
             'views' => [],
             'total_views' => 0,
             'total_followers'=>$request->user()->followers()->count(),
             'total_videos'=>$request->user()->channelVideos()->count(),
             'total_comments'=>Video::channelComments($request->user()->id)
             ->selectRaw('comments.*')
             ->count(),
         ];


             Video::views($request->user()->id)
             ->selectRaw('date(video_views.created_at) as date, count(*) as views')
             ->groupBy(DB::raw('date(video_views.created_at)'))
             ->get()
             ->each(function ($item) use (&$data){

             $data['total_views'] += $item->views;
             $data['views'][$item->date] = $item->views;

         });


       return $data;

     }


 }
