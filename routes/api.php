<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentLikeController;


Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/posts', [PostController::class, 'apiIndex']);
    // Post routes
    Route::apiResource('posts', PostController::class);
    Route::get('/users-with-liked-comments', [PostController::class, 'usersWithLikedComments']);
    // Comment routes
    Route::apiResource('posts.comments', CommentController::class);
    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
    Route::get('/poststest', [PostController::class, 'apiIndex']);
    // Like/Unlike comments
    Route::post('/comments/{comment}/like', [CommentLikeController::class, 'like']);
    Route::post('/comments/{comment}/unlike', [CommentLikeController::class, 'unlike']);

    // Special endpoint to fetch users with likes on their post comments
    Route::get('/users-with-likes', [CommentLikeController::class, 'usersWithLikes']);
});
