<?php

namespace App\Http\Controllers;
use App\Models\User;

use App\Models\Post; // Ensure this is imported
use Illuminate\Http\Request;

class PostController extends Controller
{
    
    public function index()
    {
        // Just return the Blade view without fetching posts here
        return view('posts.index');
    }
    public function apiIndex()
    {
        $posts = Post::with('user')->get();
        return response()->json($posts);
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $post = Post::create($validated);

        return response()->json($post, 201);
    }

    public function show($id)
    {
        $post = Post::with('user', 'comments.likes')->findOrFail($id);

        return view('posts.show', compact('post'));
    }


    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $post->update($request->only('title', 'body'));

        return response()->json($post);
    }



    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id != auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
    public function edit($id)
    {
        $post = Post::findOrFail($id);

        // Check if the authenticated user is the owner of the post
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('posts.edit', compact('post'));
    }

    public function usersWithLikedComments()
    {
        $users = User::whereHas('posts.comments.likes')
            ->with([
                'posts' => function ($query) {
                    $query->whereHas('comments.likes');
                },
                'posts.comments' => function ($query) {
                    $query->whereHas('likes');
                },
                'posts.comments.likes'
            ])
            ->get();

        return response()->json($users);
    }
}
