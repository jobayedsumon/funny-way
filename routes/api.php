<?php

use App\Http\Controllers\Post\CommentController;
use App\Http\Controllers\Post\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//posts
Route::resource('posts', PostController::class);

//comments
Route::post('posts/{slug}/comments', [CommentController::class, 'index']);
Route::post('posts/{slug}/comment', [CommentController::class, 'store']);

//React
Route::post('/posts/{slug}/react', [PostController::class, 'react']);

//Like
Route::post('/comments/{id}/like', [CommentController::class, 'like']);
