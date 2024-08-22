<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_like_a_comment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create(['post_id' => $post->id]);

        $this->actingAs($user)->post('/comments/' . $comment->id . '/like');

        $this->assertDatabaseHas('likes', [
            'comment_id' => $comment->id,
            'user_id' => $user->id,
        ]);
    }
}
