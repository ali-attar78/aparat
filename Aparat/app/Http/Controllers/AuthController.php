<?php

namespace App\Http\Controllers;

use App\Exceptions\RegisterVerificationException;
use App\Exceptions\UserAlreadyRegisteredException;
use App\Http\Requests\Auth\RegisterNewUserRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\Http\Requests\Auth\ResendVerificationCodeRequest;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;
use mysql_xdevapi\Exception;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private $response;
    private $now;

    public function register(RegisterNewUserRequest $request)
   {

       try {
           DB::beginTransaction();
           $field = $request->getFieldName();
           $value = $request->getFieldValue();

           if ($user = User::where($field, $value)->first()) {
               if ($user->verified_at) {
                   throw new UserAlreadyRegisteredException("شما از قبل ثبت نام کرده اید");
               }
               return response(['message' => 'کد فعالسازی قبلا ارسال شده است'], 200);
           }

           $code = random_verification_code();
           $user = User::create([

               $field => $value,
               'verify_code' => $code

           ]);


           Log::info('SEND-REGISTER-CODE-MESSAGE-TO-USER', ['code' => $code]);

           DB::commit();
           return response(['message' => 'کاربر ثبت موقت شد'], 200);
       }

       catch (Exception $exception){
           DB::rollBack();
           return response([
               "message" => "خطایی رخ  داده است"
           ]);
       }
   }

   public function registerVerify(RegisterVerifyUserRequest $request)
   {
       $field = $request->getFieldName();
       $value=$request->getFieldValue();
       $code=request()->code;

       $user = User::where([
           'verify_code'=>$code,
            $field => $value
       ])->first();



        if (empty($user)){
        throw new ModelNotFoundException('کاربری با کد مورد نظر یافت نشد');
    }

        $user->verify_code=null;
        $user->verified_at= now();
        $user->save();


       return response($user, 200);

   }

   public function resendVerificationCode(ResendVerificationCodeRequest $request)
   {
       $field=$request->getFieldName();
       $value=$request->getFieldValue();
       $user= User::where($field,$value)->whereNull('verified_at')->first();

       if (!empty($user)){
           $dateDiff=now()->diffInMinutes($user->updated_at);

           if ($dateDiff>config('auth.resend_verification_code_diff_in_minutes',60)){
               $user->verify_code=random_verification_code();
               $user->save();
           }

           Log::info('RESEND-REGISTER-CODE-MESSAGE-TO-USER',['code'=>$user->verify_code]);
           return response([
               'message'=> 'کد مجددا ارسال شد'
           ],200);
       }

       throw new ModelNotFoundException("کاربری یا این مشخصات یافت نشد یا قبلا فعال سازی شده است");
   }

}
