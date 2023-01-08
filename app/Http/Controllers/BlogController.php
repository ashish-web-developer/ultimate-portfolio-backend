<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Models\Blog;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BlogController extends Controller
{

    public function AddBlog(Request $request, Response $response){
        $Validated = Validator::make($request->all(),[
            "title"=>"required|string",
            "data"=>"required",
            "status"=>"required|boolean",
            "featured_image"=>"required|string"
        ]);
        if($Validated){
            if($request->id){
                $blog = Blog::find($request->id);
                Log::info($blog);
                $blog->blogs = json_encode(collect($request->data));
                $blog->title = $request->title;
                $blog->status = $request->status;
                $blog["featured image"] = $request->featured_image;
                $blog["user"] = $request->user->name;
                $blog["email"] = $request->user->email;
                $blog->save();
                return response()->json(["message"=>"Blog have got updated successfully"]);

            }else{
                $user = $request->user()->toArray();
                $blog = new Blog();
                $blog->blogs = json_encode(collect($request->data));
                $blog->title=$request->title;
                $blog->status=$request->status;
                $blog["featured image"] = $request->featured_image;
                $blog["user"] = $user["name"];
                $blog["email"] = $user["email"];
                $blog["slug"] = Str::slug($request->title,"-");
                $blog->save();
                return response()->json([
                    "success"=>true,
                    "message"=>"Blog got created",
                    "blog"=> $blog
                ]);
            }
        }else{
            return response()->json([
                "success"=>false,
                "message"=>"Blog not created",
                "error"=>$Validated->errors()
            ]);
        }
    }
    //
    public function getBlog(Request $request, Response $response){
        if($request->id){
            $blog = Blog::find($request->id);
            $blog["blogs"]=json_decode($blog["blogs"],true);
            return response()->json($blog);

        }
        $blogs = Blog::all()->toArray();
        foreach($blogs as $key=>$value){
            $blogs[$key]["blogs"] = json_decode($blogs[$key]["blogs"],true);
        }
        return response()->json($blogs);
    }

    public function uploadImage(Request $request){
        $file = $request->file("image");
        $name = $file->hashName();
        Storage::put("public",$file);
        $file_url = asset("/storage/$name");
        Log::info($file_url);
        return response()->json([
            "success"=>1,
                "file"=>[
                    "url"=>$file_url
                ]
            ]);
    }
    public function featuredImageUpload(Request $request){
        $validateFile = Validator::make($request->all(),[
            "image"=> "required|image|mimes:jpeg,png,jpg"
        ]);
        if($validateFile){
            try{
                $file = $request->file("image");
                $name = $file->hashName();
                Storage::put("public/featured-image",$file);
                $file_url = asset("/storage/featured-image/$name");
                return response()->json([
                    "success"=>1,
                    "message"=>"File Uploaded Successfully",
                    "file"=>[
                        "url"=>$file_url
                    ]
                ],200);
            }catch(\Throwable $th){
                return response()->json([
                    "success"=>false,
                    "message"=>"File Not Uploaded",
                    "error"=>$th->getMessage()
                ],500);
            }
        }else{
            return response()->json([
                "success"=>false,
                "message"=>"File Not Uploaded",
                "error"=>$validateFile->errors()
            ],401);
        }
    }
}
