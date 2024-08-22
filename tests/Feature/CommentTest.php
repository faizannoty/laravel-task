<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_comment_on_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user)->post('/posts/' . $post->id . '/comments', [
            'comment' => 'This is a test comment.',
        ]);

        $this->assertDatabaseHas('comments', [
            'comment' => 'This is a test comment.',
            'post_id' => $post->id,
        ]);
    }
}

