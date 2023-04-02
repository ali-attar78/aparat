<?php

use App\Http\Controllers\ChannelController;
use App\Http\Controllers\VideoController;
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



Route::group([],function ($router){

    $router->group(['namespace' => 'Laravel\Passport\Http\Controllers'],function ($router) {

        $router->post('login',[
            'as' => 'auth.login',
            'middleware' => ['throttle'],
            'uses' => 'AccessTokenController@issueToken',

        ]);
    });

    $router->post('register', [AuthController::class, 'register'])->name('auth.register');

    $router->post('register-verify', [AuthController::class, 'registerVerify'])->name('auth.register-verify');

    $router->post('resend-verification-code', [AuthController::class, 'resendVerificationCode'])->name('auth.register.resend.verification.code');

});


Route::group([],function ($router){

    $router->post('change-email', [UserController::class, 'changeEmail'])->middleware('auth:api')->name('change.email'); //['auth:api']

    $router->post('change-email-submit', [UserController::class, 'changeEmailSubmit'])->middleware('auth:api')->name('change.email.submit'); //['auth:api']

    $router->match(['post', 'put'], 'change-password', [UserController::class, 'changePassword'])->middleware('auth:api')->name('password.change');


});

Route::group(['prefix'=>'/channel'],function ($router){

    $router->put('/{id?}', [ChannelController::class, 'update'])->middleware('auth:api')->name('channel.update'); //['auth:api']

    $router->match(['post', 'put'], '/', [ChannelController::class, 'uploadBanner'])->middleware('auth:api')->name('channel.upload.banner');

    $router->match(['post', 'put'], '/socials', [ChannelController::class, 'updateSocials'])->middleware('auth:api')->name('channel.update.socials');


});

Route::group(['prefix'=>'/video'],function ($router){

    $router->post('/', [VideoController::class, 'upload'])->middleware('auth:api')->name('video.upload'); //['auth:api']



});




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
