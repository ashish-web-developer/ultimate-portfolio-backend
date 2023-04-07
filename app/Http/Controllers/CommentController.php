<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Blog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::all();
        return response()->json([
            "comments"=>$comments
        ]);
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateComment = Validator::make($request->all(),[
            "body"=>"required",
        ]);
        if($validateComment->fails()){
            return response()->json([
                "success"=>false,
                "message"=> "Not created",
                "error" => $validateComment->errors()
            ],401);
        }
        Comment::create([
            "body"=> $request->body,
            "user_id"=>$request->user()->id,
            "blog_id"=>$request->blog_id
        ]);
        return response()->json([
            "comments"=>Comment::with("like","user")->where("blog_id",$request->blog_id)->get(),
            "success"=>true,
            "message"=>"successfully created",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        return response()->json([
            "comment"=>$comment
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $validateComment = Validator::make($request->all(),[
            "body"=>"required"
        ]);
        if($validateComment->fails()){
            return response()->json([
                "success"=>false,
                "message"=> "Not created",
                "error" => $validateComment->errors()
            ],401);
        }
        $comment->update([
            "body"=>$request->body
        ]);
        return response()->json([
            "success"=>true,
            "message"=>"Updated Successfully",
            "comment"=>$comment
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json([
            "success"=>false,
            "message"=>"Successfully Deleted",
        ]);
    }
    public function upvote(Request $request)
    {
        $validateLike = Validator::make($request->all(),[
            "blog_id"=>"required",
            "comment_id"=>"required"
        ]);
        if($validateLike->fails()){
            return response()->json([
                "success"=>false,
                "message"=>"failed",
                "error"=>$validateLike->errors()
            ]);
        }
        $like = Like::firstOrCreate([
            "user_id"=>$request->user()->id,
            "blog_id"=>$request->blog_id,
            "comment_id"=>$request->comment_id,
        ]);
        $like->like = 1;
        $like->save();
        $comment = Comment::where("blog_id",$request->blog_id)->where("id",$request->comment_id)->with("user","like")->first();
        return response()->json([
            "success"=>true,
            "message"=>"Liked Successfully",
            "comment"=>$comment
        ]);
    }
    public function downvote(Request $request)
    {
        $validateLike = Validator::make($request->all(),[
            "blog_id"=>"required",
            "comment_id"=>"required"
        ]);
        if($validateLike->fails()){
            return response()->json([
                "success"=>false,
                "message"=>"failed",
                "error"=>$validateLike->errors()
            ]);
        }
        $like = Like::firstOrCreate([
            "user_id"=>$request->user()->id,
            "blog_id"=>$request->blog_id,
            "comment_id"=>$request->comment_id,
        ]);
        $like->like = 0;
        $like->save();
        $comment = Comment::where("blog_id",$request->blog_id)->where("id",$request->comment_id)->with("user","like")->first();
        return response()->json([
            "success"=>true,
            "message"=>"Liked Successfully",
            "comment"=>$comment
        ]);
    }
}
