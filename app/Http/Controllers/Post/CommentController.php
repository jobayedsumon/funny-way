<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{

    public function index(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->first();

        if (!$post) {
            return response()->json([
                'status' => 'error',
                'message' => 'Post not found'
            ]);
        }

        $parents = $post->comments()->whereNull('parent_id');
        $total_comments = $parents->count();

        if ($request->get('all')) {
            $comments = $parents->with('replies')->get();
        } else {
            $comments = $parents->with('replies')->limit(2)->get();
        }

        return response()->json([
            'status' => 'success',
            'comments' => $comments,
            'total_comments' => $total_comments
        ]);
    }
    public function store(Request $request, $slug)
    {
        $validator = Validator::make( $request->all(), [
            'name' => 'required',
            'picture' => 'required',
            'email' => 'required',
            'sub' => 'required',
            'content' => 'required'
        ] );

        if ( $validator->fails() ) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ]);
        }

        $post = Post::where('slug', $slug)->first();

        if (!$post) {
            return response()->json([
                'status' => 'error',
                'message' => 'Post not found'
            ]);
        }

        $comment = new Comment();

        $comment->post_id = $post->id;
        if ($request->get('parent_id')) {
            $comment->parent_id = $request->get('parent_id');
        }
        $comment->name = $request->get('name');
        $comment->picture = $request->get('picture');
        $comment->email = $request->get('email');
        $comment->sub = $request->get('sub');
        $comment->content = $request->get('content');

        $comment->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Comment added successfully'
        ]);
    }
}
