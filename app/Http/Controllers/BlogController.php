<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Models\Blog;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{

    public function AddBlog(Request $request, Response $response){
        $Validated = $request->validate([
            "title"=>"required",
            "data"=>"required",
            "status"=>"required",
            "featured_image"=>"required"
        ]);
        if($Validated){
            if($request->id){
                $blog = Blog::find($request->id);
                Log::info($blog);
                $blog->blogs = json_encode(collect($request->data));
                $blog->title=$request->title;
                $blog->status=$request->status;
                $blog["featured image"] = $request->featured_image;
                $blog->save();
                return response()->json(["message"=>"Blog have got updated successfully"]);

            }else{
                $blog = new Blog();
                $blog->blogs = json_encode(collect($request->data));
                $blog->title=$request->title;
                $blog->status=$request->status;
                $blog["featured image"] = $request->featured_image;
                $blog->save();
                return response()->json(["message"=>"Blog have got created successfully"]);
            }
        }
        return response()->json(["message",["param is missing"]],Response::HTTP_BAD_REQUEST);
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
        $file = $request->file("image");
        $name = $file->hashName();
        Storage::put("public/featured-image",$file);
        $file_url = asset("/storage/featured-image/$name");
        Log::info($file_url);
        return response()->json([
            "success"=>1,
            "file"=>[
                "url"=>$file_url
            ]
        ]);
    }
}
