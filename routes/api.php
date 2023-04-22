<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\CommentController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware'=>'auth:sanctum'],function(){
    Route::get("/logout",[UserController::class,"logoutUser"]);
    Route::post("/blog",[BlogController::class,"addBlog"]);
    Route::post("/featured-image",[BlogController::class,"featuredImageUpload"]);
    Route::prefix("comment")->group(function(){
        Route::get('', [CommentController::class, 'index']);
        Route::post('create', [CommentController::class, 'store']);
        Route::post("upvote",[CommentController::class, 'upvote']);
        Route::post("downvote",[CommentController::class, 'downvote']);
    });
});


Route::post("/register",[UserController::class,"createUser"]);
Route::post("/login",[UserController::class,"loginUser"]);
Route::post("/get-blog",[BlogController::class,"getBlog"]);
Route::post("/upload",[BlogController::class,"uploadImage"]);
Route::get("/abc",function(){
    return "random";
});