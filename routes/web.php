<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;

// Route to display the login page
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Route to display the registration page
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Route to display the form for creating a new post
Route::get('/posts-create', function () {
    return view('posts.create');
})->name('posts-create');

// Route to display the users with liked comments page
Route::get('/', function () {
    return view('users_with_liked_comments');
}); 

// Route to display posts in the Blade view

// API routes for handling posts
Route::resource('/posts', PostController::class); 

// Route to handle user logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
