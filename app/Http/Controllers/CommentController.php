<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class CommentController extends Controller
{
    public function index($postId)
    {
        $post = Post::findOrFail($postId);
        // In your Controller method
        $comments = Comment::where('post_id', $postId)
            ->withCount('likes') // Assuming you have a 'likes' relationship in the Comment model
            ->with('user') // Include the user who posted the comment
            ->get();

        return response()->json($comments);

        return $post->comments()->with('user')->get();
    }

// In CommentController.php
public function store(Request $request, $postId)
{
    $validated = $request->validate([
        'comment' => 'required|string',
        'user_id' => 'required|integer',
        'post_id' => 'required|integer',
    ]);

    // Check if the post exists
    $post = Post::find($validated['post_id']);
    if (!$post) {
        return response()->json(['message' => 'Post not found'], 404);
    }

    // Create a new comment using mass assignment
    $comment = Comment::create([
        'comment' => $validated['comment'],
        'user_id' => $validated['user_id'],
        'post_id' => $validated['post_id'],
    ]);

    return response()->json($comment, 201);
}



    public function show($postId, $id)
    {
        $post = Post::findOrFail($postId);
        $comment = $post->comments()->with('user')->findOrFail($id);

        return response()->json($comment);
    }

    public function update(Request $request, $postId, $id)
    {
        $post = Post::findOrFail($postId);
        $comment = $post->comments()->findOrFail($id);

        if ($comment->user_id != auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'comment' => 'sometimes|required|string',
        ]);

        $comment->update($validatedData);

        return response()->json($comment);
    }

    public function destroy($postId, $id)
    {
        $post = Post::findOrFail($postId);
        $comment = $post->comments()->findOrFail($id);

        if ($comment->user_id != auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
