<?php

namespace App\Http\Controllers;

use App\Exceptions\RegisterVerificationException;
use App\Exceptions\UserAlreadyRegisteredException;
use App\Http\Requests\Auth\RegisterNewUserRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;
use mysql_xdevapi\Exception;

class AuthController extends Controller
{
    private $response;
    private $now;

    public function register(RegisterNewUserRequest $request)
   {
       $field=$request->has('email')?'email':'mobile';
       $value=$request->input($field);

       $code=random_int(100000,999999);

       if ($user = User::where($field,$value)->first()){
           if ($user->verified_at){
               throw new UserAlreadyRegisteredException("شما از قبل ثبت نام کرده اید");
           }
           return response(['message'=>'کد فعالسازی قبلا ارسال شده است'],200);
       }


       $user =User::create([

            $field=>$value,
           'verify_code'=>$code

           ]);

       Log::info('SEND-REGISTER-CODE-MESSAGE-TO-USER',['code'=>$code]);


       return response(['message'=>'کاربر ثبت موقت شد'],200);
   }

   public function registerVerify(RegisterVerifyUserRequest $request)
   {
       $field=$request->has('email')?'email':'mobile';

       $code=request()->code;
       $user = User::where([
           'verify_code'=>$code,
            $field => $request->input($field)
       ])->first();

        if (empty($user)){
        throw new ModelNotFoundException('کاربری با کد مورد نظر یافت نشد');
    }

        $user->verify_code=null;
        $user->verified_at= now();
        $user->save();


       return response($user, 200);

   }


}
