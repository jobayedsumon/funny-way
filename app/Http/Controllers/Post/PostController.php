<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::select('id', 'title', 'slug', 'content', 'created_at')
                ->whereHas('category', function ($query) {
                    $query->where('category_type', 1);
                })
                ->with('image:path,post_id')
                ->orderBy('datetime', 'desc')
                ->get();

//        $most_viewed = Post::withCount('comments')->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
//            ->orderBy('total_views', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'posts' => $posts,
//            'most_viewed' => $most_viewed
        ]);
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->with('image:path,post_id')
                ->withCount('comments', 'reacts')->with('category:slug,category_type,id')->first();
        $post->total_views += 1;
        $post->save();

        if ($post->category->category_type == 1) {
            $related_posts = Post::where('id', '!=', $post->id)
                ->where('category_id', $post->category_id)
                ->select('id', 'title', 'slug', 'content', 'created_at')
                ->with('image:path,post_id')->orderBy('datetime', 'desc')->limit(8)->get();
        } else {
            $related_posts = [];
        }



        return response()->json([
            'status' => 'success',
            'post' => $post,
            'related_posts' => $related_posts
        ]);
    }

    public function react(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'sub' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ]);
        }

        $post = Post::where('slug', $slug)->first();
        $user = User::where('unique_id', $request->get('sub'))->orWhere('email', $request->get('email'))->first();

        $react = $post->reacts()->where('user_id', $user->id)->first();

        if ($react) {
            $react->delete();
        } else {
            $post->reacts()->create([
                'user_id' => $user->id,
            ]);
        }

        $post = Post::where('slug', $slug)->with('image:path,post_id')->withCount('comments', 'reacts')->first();

        return response()->json([
            'status' => 'success',
            'post' => $post
        ]);
    }

    public function search($query = "")
    {
        $query = strtolower($query);

        $posts = Post::select('id', 'title', 'slug', 'content', 'created_at')
                ->whereHas('category', function ($query) {
                    $query->where('category_type', 1);
                })
                ->whereRaw("(LOWER(title) LIKE '%$query%' OR LOWER(content) LIKE '%$query%')")
                ->with('image:path,post_id')
                ->orderBy('datetime', 'desc')
                ->get();

        return response()->json([
            'status' => 'success',
            'posts' => $posts
        ]);
    }
}
