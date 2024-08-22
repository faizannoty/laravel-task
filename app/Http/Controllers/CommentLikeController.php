<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\User;


class CommentLikeController extends Controller
{
    public function like($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        $like = CommentLike::firstOrCreate([
            'comment_id' => $comment->id,
            'user_id' => auth()->id(),
        ]);

        return response()->json(['message' => 'Comment liked successfully']);
    }

    public function unlike($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        $like = CommentLike::where('comment_id', $comment->id)
                           ->where('user_id', auth()->id())
                           ->first();

        if ($like) {
            $like->delete();
            return response()->json(['message' => 'Comment unliked successfully']);
        }

        return response()->json(['message' => 'You have not liked this comment'], 400);
    }

    public function usersWithLikes()
    {
        $users = User::whereHas('posts.comments.likes')->with('posts.comments.likes')->get();

        return response()->json($users);
    }
}
