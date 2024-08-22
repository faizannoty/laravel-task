<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\CommentLike;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_with_liked_comments_on_their_posts_are_returned()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $post1 = Post::factory()->create(['user_id' => $user1->id]);
        $post2 = Post::factory()->create(['user_id' => $user2->id]);

        $comment1 = Comment::factory()->create(['post_id' => $post1->id]);
        $comment2 = Comment::factory()->create(['post_id' => $post2->id]);

        CommentLike::factory()->create(['comment_id' => $comment1->id, 'user_id' => $user2->id]);
        CommentLike::factory()->create(['comment_id' => $comment2->id, 'user_id' => $user1->id]);

        $response = $this->get('/users-with-liked-comments');

        $response->assertStatus(200);
        $response->assertJsonCount(2); // Expecting both users to be returned
    }
}

