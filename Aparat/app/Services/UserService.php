<?php

namespace App\Services;

 use App\Exceptions\UserAlreadyRegisteredException;
 use App\Http\Requests\Auth\RegisterNewUserRequest;
 use App\Http\Requests\Auth\RegisterVerifyUserRequest;
 use App\Http\Requests\Auth\ResendVerificationCodeRequest;
 use App\Http\Requests\User\ChangeEmailRequest;
 use App\Http\Requests\User\ChangeEmailSubmitRequest;
 use App\Models\User;
 use Illuminate\Database\Eloquent\ModelNotFoundException;
 use Illuminate\Support\Facades\Cache;
 use Illuminate\Support\Facades\DB;
 use Illuminate\Support\Facades\Log;
 use mysql_xdevapi\Exception;

 class UserService extends BaseService
{

     const CHANGE_EMAIL_CACHE_KEY='change.email.for.user.';


     public static function registerNewUser(RegisterNewUserRequest $request){

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
            if ($exception instanceof UserAlreadyRegisteredException){
                throw $exception;
            }
            Log::error($exception);
            return response([
                "message" => "خطایی رخ  داده است"
            ]);
        }


    }

    public static function registerNewUserVerify(RegisterVerifyUserRequest $request)
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

     public static function resendVerificationCodeUser(ResendVerificationCodeRequest $request)
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

     public static function changeEmail(ChangeEmailRequest $request)
     {

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

         catch (\Lcobucci\JWT\Exception $e){
             Log::error($e);
             return response([
                 'message'=>'server error'
             ],500);
         }

     }

     public static function changeEmailSubmit( ChangeEmailSubmitRequest $request)
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
