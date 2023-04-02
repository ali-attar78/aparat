<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ChangeEmailSubmitRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Services\UserService;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Lcobucci\JWT\Exception;
use function Symfony\Component\String\u;

class UserController extends Controller
{

    public function changeEmail(ChangeEmailRequest $request)
    {
       return UserService::changeEmail($request);
    }

    public function changeEmailSubmit(ChangeEmailSubmitRequest $request)
    {
        return UserService::changeEmailSubmit($request);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        return UserService::changePassword($request);
    }

}
