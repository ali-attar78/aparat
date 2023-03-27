<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
