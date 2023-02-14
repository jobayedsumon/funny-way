<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('image')->orderBy('created_at', 'desc')->get();
        $most_viewed = Post::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->orderBy('total_views', 'desc')->get();

        return response()->json([
            'posts' => $posts,
            'most_viewed' => $most_viewed
        ]);
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->with('image', 'user.user_meta')->first();
        $post->total_views += 1;
        $post->save();

        return response()->json([
            'post' => $post
        ]);
    }
}
