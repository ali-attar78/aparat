<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterNewUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;

class AuthController extends Controller
{
   public function register(RegisterNewUserRequest $request)
   {
       $field=$request->has('email')?'email':'mobile';
       $value=$request->input($field);
       $code='123456';

       Cache::put('user-auth-register-'.$value,compact('code','field'),now()->addDays(5));

       Log::info('SEND-REGISTER-CODE-MESSAGE-TO-USER',['code'=>$code]);

       return response(['message'=>'کاربر ثبت موقت شد'],200);
   }
}
