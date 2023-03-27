<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('priority', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'categories' => $categories
        ]);
    }

    public function show($slug)
    {
        $category = Category::where('slug', $slug)->first();

        $posts = $category->posts()->select('id', 'title', 'slug', 'content', 'created_at')
            ->with('image:path,post_id')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'category' => $category,
            'posts' => $posts
        ]);
    }

}
