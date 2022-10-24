<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function createUser(Request $request)
    {
        try{
            $validateUser = Validator::make($request->all(),[
                "name"=>"required",
                "email"=>"required|email|unique:users,email",
                "password"=>"required"
            ]);
            if($validateUser->fails()){
                return response()->json([
                    "status"=>false,
                    "message"=>"Not created",
                    "error"=>$validateUser->errors()
                ],401);
            }
            $user = User::create([
                "name"=>$request->name,
                "email"=>$request->email,
                "password"=>Hash::make($request->password)
            ]);
            $token = $user->createToken("API TOKEN")->accessToken;
            return response()->json([
                "status"=>true,
                "message"=>"User created Successfully",
                "user"=>$user,
                "token"=>$token
            ],200);
        }
        catch(\Throwable $th){
            return response()->json([
                "status"=>false,
                "message"=>"Not created",
                "error"=>$th->getMessage()
            ],401);
        }
    }
    public function loginUser(Request $request){
        try{
            $remember = true;
            $validateUser = Validator::make($request->all(),[
                "email"=>"required|email",
                "password"=>"required"
            ]);
            if($validateUser->fails()){
                return response()->json([
                    "status"=>false,
                    "message"=>"Not Authenticated",
                    "errors"=>$validateUser->errors()
                ],401);
            }
            if(Auth::attempt($request->all(),$remember)){
                $user = Auth::user();
                $token = $user->createToken("API TOEKN")->accessToken;
                Log::info($request);
                return response()->json([
                    "status"=>true,
                    "message"=>"Authenticated",
                    "user"=>$user,
                    "token"=>$token
                ],200);
            }else{
                return response()->json([
                    "status"=>false,
                    "message"=>"Unauthenticated",
                ],401);
            }
        }
        catch(\Throwable $th){
            return response()->json([
                "status"=>false,
                "message"=> "Not Authenticated",
                "errors"=>$th->getMessage()
            ],401);
        }
    }
    public function logoutUser(Request $request){
        try{
            $token =  $request->user()->token();
            $token->revoke();
            return response()->json([
                "success"=>true,
                "message"=>"User logged out successfully",
            ],200);
        } catch(\Throwable $th){
            return response()->json([
                "success"=>false,
                "message"=>$th->getMessage()
            ],401);
        }
    }
    //
}
