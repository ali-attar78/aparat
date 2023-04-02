<?php

namespace App\Services;



 use App\Http\Requests\Video\UploadVideoRequest;
 use Illuminate\Support\Str;
 use Lcobucci\JWT\Exception;

 class VideoService extends BaseService
{


     public static function upload(UploadVideoRequest $request)
     {


         try {
             $video=$request->file('video');
             $fileName= time() . Str::random(10);
             $path=public_path('videos/tmp');
             $video->move($path,$fileName);

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
 }
