<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;

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



Route::post("/register",[AuthController::class,"createUser"]);
Route::post("/login",[AuthController::class,"loginUser"]);
Route::post("/blog",[BlogController::class,"addBlog"]);
Route::post("/get-blog",[BlogController::class,"getBlog"]);
Route::post("/upload",[BlogController::class,"uploadImage"]);
Route::post("/featured-image",[BlogController::class,"featuredImageUpload"]);