<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;

class Like extends Model
{
    use HasFactory;
    protected $fillable = ["user_id","blog_id","comment_id","like"];
}
