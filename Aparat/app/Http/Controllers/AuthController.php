<?php

namespace App\Http\Controllers;

use App\Exceptions\RegisterVerificationException;
use App\Exceptions\UserAlreadyRegisteredException;
use App\Http\Requests\Auth\RegisterNewUserRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\Http\Requests\Auth\ResendVerificationCodeRequest;
use App\Models\Channel;
use App\Models\User;
use App\Services\BaseService;
use App\Services\UserService;
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
       return  UserService::registerNewUser($request);
   }

   public function registerVerify(RegisterVerifyUserRequest $request)
   {
       return UserService::registerNewUserVerify($request);
   }

   public function resendVerificationCode(ResendVerificationCodeRequest $request)
   {
      return UserService::resendVerificationCodeUser($request);
   }

}
