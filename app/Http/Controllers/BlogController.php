<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{

    public function AddBlog(Request $request, Response $response){
        $blog = new Blog();
        $blog->blogs = json_encode($request->collect());
        $blog->save();
        return response()->json(["message"=>"blog have got created successfully"]);
    }
    //
    public function getBlog(Response $response){
        $blogs = Blog::all()->toArray();
        foreach($blogs as $key=>$value){
            $blogs[$key]["blogs"] = json_decode($blogs[$key]["blogs"],true);
        }
        return response()->json($blogs);
    }

    public function uploadImage(Request $request){
        $file = $request->file("image");
        $name = $file->hashName();
        return response()->json([
            "success"=>1,
                "file"=>[
                    "url"=>"http://localhost:8000/storage/$name"
                ]
            ]);
    }
}
