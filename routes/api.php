<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Auth\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware'=>'auth:api'],function(){
    Route::get("/logout",[UserController::class,"logoutUser"]);
    Route::post("/blog",[BlogController::class,"addBlog"]);
    Route::post("/featured-image",[BlogController::class,"featuredImageUpload"]);
});


Route::post("/register",[UserController::class,"createUser"]);
Route::post("/login",[UserController::class,"loginUser"]);
Route::post("/get-blog",[BlogController::class,"getBlog"]);
Route::post("/upload",[BlogController::class,"uploadImage"]);