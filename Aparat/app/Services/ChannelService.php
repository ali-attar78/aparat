<?php

namespace App\Services;

 use App\Http\Requests\Channel\UpdateChannelRequest;
 use App\Http\Requests\Channel\UploadBannerForChannelRequest;
 use App\Models\Channel;
 use App\Models\User;
 use http\Env\Response;
 use Illuminate\Auth\Access\AuthorizationException;
 use Illuminate\Support\Facades\DB;
 use Illuminate\Support\Facades\Log;
 use Lcobucci\JWT\Exception;
 use Illuminate\Support\Str;

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
             $banner->move(public_path('channel-banners'),$fileName);

             $channel=auth()->user()->channel;
             if ($channel->banner){
                 unlink(public_path($channel->banner));
             }
             $channel->banner='channel-banners/' . $fileName;
             $channel->save();

             return response([
                 'banner' => url('channel-banners/' . $fileName)
             ],200);
         }

         catch (Exception $exception){

             return response([
                 'message'=>'خطایی رخ داده است'
             ],500);
         }
     }


 }
