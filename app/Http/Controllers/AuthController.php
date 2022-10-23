<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function createUser(Request $request)
    {
        try {

            $validateUser = Validator::make($request->all(),
            [
                'name'=>'required',
                'email'=>'required|email|unique:users,email',
                'password'=>'required'
            ]);
            if($validateUser->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=>'validation error',
                    'errors'=>$validateUser->errors()
                ],401);
            };

            $user = User::create([
                'name'=> $request->name,
                'email'=> $request->email,
                'password'=>Hash::make($request->password)
            ]);

            return  response()->json([
                'status'=>true,
                'message'=>"User created Successfully",
                'token'=>$user->createToken("API TOKEN")->plainTextToken
            ],200);
        }
        catch (\Throwable $th){
            return response()->json([
                'status'=>false,
                'message'=>$th->getMessage()
            ],401);
        }
    }

    public function loginUser(Request $request){
        try{
            $validateUser = Validator::make($request->all(),[
                "email"=>"required|email",
                "password"=>"required"
            ]);
            if($validateUser->fails()){
                return response()->json([
                    "status"=>false,
                    "message"=>"validation error",
                    "errors"=>$validateUser->errors()
                ],401);
            }
            if(!Auth::attempt($request->only(["email","password"]))){
                return response()->json([
                    "status"=>false,
                    "message"=>"Email & Password does't match"
                ]);
            }
            $user = User::where("email",$request->email)->first();
            return response()->json([
                "status"=>true,
                "message"=>"User Logged In Successfully",
                "token" =>$user->createToken("API TOKEN")->plainTextToken
            ],200);
        }
        catch(\Throwable $th){
            return response()->json([
                "status"=>false,
                "message"=>$th->getMessage()
            ]);
        }
    }
}
