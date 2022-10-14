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
            $blog = new Blog();
            $blog->blogs = json_encode(collect($request->data));
            $blog->title=$request->title;
            $blog->status=$request->status;
            $blog["featured image"] = $request->featured_image;
            $blog->save();
            return response()->json(["message"=>"blog have got created successfully"]);
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
        return response()->json([
            "success"=>1,
                "file"=>[
                    "url"=>"http://localhost:8000/storage/$name"
                ]
            ]);
    }
    public function featuredImageUpload(Request $request){
        $file = $request->file("image");
        $name = $file->hashName();
        Storage::put("public/featured-image",$file);
        return response()->json([
            "success"=>1,
            "file"=>[
                "url"=>"http://localhost:8000/storage/featured-image/$name"
            ]
        ]);
    }
}
