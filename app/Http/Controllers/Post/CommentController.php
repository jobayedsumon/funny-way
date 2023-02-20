<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Models\UserMeta;
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
            $comments = $parents->with('replies', 'likes.user')->latest()->get();
        } else {
            $comments = $parents->with('replies', 'likes.user')->latest()->limit(2)->get();
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

        $user = User::where('unique_id', $request->get('sub'))->orWhere('email', $request->get('email'))->first();

        if (!$user) {

            $user = new User();
            $user->unique_id = $request->get('sub');
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->save();

            $user_meta = new UserMeta();
            $user_meta->image = $request->get('picture');

            $user->user_meta()->save($user_meta);
        }

        if (!$post) {
            return response()->json([
                'status' => 'error',
                'message' => 'Post not found'
            ]);
        }

        $comment = new Comment();

        $comment->post_id = $post->id;
        $comment->user_id = $user->id;
        $comment->parent_id = $request->get('parent_id');
        $comment->content = $request->get('content');

        $comment->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Comment added successfully'
        ]);
    }

    public function like(Request $request, $id)
    {
        $validator = Validator::make( $request->all(), [
            'sub' => 'required',
        ] );

        if ( $validator->fails() ) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ]);
        }

        $comment = Comment::where('id', $id)->first();
        $user = User::where('unique_id', $request->get('sub'))->orWhere('email', $request->get('email'))->first();

        $like = $comment->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
        } else {
            $comment->likes()->create([
                'user_id' => $user->id,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Successful'
        ]);
    }
}
