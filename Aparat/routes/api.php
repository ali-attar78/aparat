<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['namespace' => 'Laravel\Passport\Http\Controllers'],function ($router) {

    $router->post('login',[
        'as' => 'auth.login',
        'middleware' => ['throttle'],
        'uses' => 'AccessTokenController@issueToken',

    ]);
});


Route::post('register', [AuthController::class, 'register'])->name('auth.register');

Route::post('register-verify', [AuthController::class, 'registerVerify'])->name('auth.register-verify');

Route::post('resend-verification-code', [AuthController::class, 'resendVerificationCode'])->name('auth.register.resend.verification.code');


Route::group(['namespace' => 'App\Http\Controllers'],function ($router) {

    $router->post('change-email',[
        'middleware' =>['auth:api'],
        'as' => 'change.email',
        'uses' => 'UserController@changeEmail',

    ]);

});


Route::group(['namespace' => 'App\Http\Controllers'],function ($router) {

    $router->post('change-email-submit',[
        'middleware' =>['auth:api'],
        'as' => 'change.email.submit',
        'uses' => 'UserController@changeEmailSubmit',

    ]);

});




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
