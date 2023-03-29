<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ChangeEmailSubmitRequest;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Lcobucci\JWT\Exception;
use function Symfony\Component\String\u;

class UserController extends Controller
{
    const CHANGE_EMAIL_CACHE_KEY='change.email.for.user.';

    public function changeEmail(ChangeEmailRequest $request){

        try{
            $email=$request->email;
            $userId=auth()->id();
            $code=random_verification_code();
            $expireDate=now()->addMinutes(config('auth.change_email_cache_expiration',1440));
            Cache::put(self::CHANGE_EMAIL_CACHE_KEY . $userId,compact('email','code'),$expireDate );


            return response([
                'message'=>'ایمیل ارسال شد لطفا بررسی کنید'
            ],500);
        }

      catch (Exception $e){
            Log::error($e);
            return response([
               'message'=>'server error'
            ],500);
      }
    }

    public function changeEmailSubmit(ChangeEmailSubmitRequest $request)
    {

        $userId=auth()->id();
        $cacheKey=self::CHANGE_EMAIL_CACHE_KEY . $userId;

        $cache= Cache::get(self::CHANGE_EMAIL_CACHE_KEY. $userId);
        if (empty($cache)|| $cache['code'] != $request->code){
            return Response([
                'message' => 'درخواست نامعتبر است'
            ],400);
        }

        $user=auth()->user();
        $user->email=$cache['email'];
        $user->save();
        Cache::forget($cacheKey);

        return Response([
            'message' => 'ایمیل با موفقیت تغییر یافت'
        ],200);

    }

}
