<?php

use App\Enums\VideoStatusEnum;
use App\Models\Comment;
use App\Models\User;
use App\Models\Video;

describe('CommentControler store', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->video = Video::factory()->create([
            'status' => VideoStatusEnum::Processed,
        ]);
    });

    it('should create a comment for a video', function () {
        $comment = [
            'content' => 'This is a comment',
        ];

        $response = $this->actingAs($this->user)
            ->post(route('videos.comments.store', $this->video->id), $comment);

        $response->assertStatus(302);

        $this->assertDatabaseHas('comments', [
            'user_id' => $this->user->id,
            'video_id' => $this->video->id,
            'content' => $comment['content'],
        ]);
    });

    it('should not create a comment for a video if the content is missing', function () {
        $comment = [
            'content' => '',
        ];

        $response = $this->actingAs($this->user)
            ->post(route('videos.comments.store', $this->video->id), $comment);

        $response->assertSessionHasErrors('content');
    });

    it('should not create a comment for a video if the user is not authenticated', function () {
        $comment = [
            'content' => 'This is a comment',
        ];

        $response = $this->post(route('videos.comments.store', $this->video->id), $comment);

        $response->assertRedirect(route('login'));
    });

    it('should not create a comment for a video if the video does not exist', function () {
        $comment = [
            'content' => 'This is a comment',
        ];

        $this->video->delete();

        $response = $this->actingAs($this->user)
            ->post(route('videos.comments.store', 1), $comment);

        $response->assertStatus(404);
    });
});

describe('CommentController destroy', function () {
    it('should delete a comment', function () {
        $user = User::factory()->create();
        $video = Video::factory()->create([
            'status' => VideoStatusEnum::Processed,
        ]);

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'video_id' => $video->id,
        ]);

        $route = route('videos.comments.destroy', [$video->id, $comment->id]);

        $response = $this->actingAs($user)
            ->delete($route);

        $response->assertStatus(302);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    });

    it('should not delete a comment if the user is not authenticated', function () {
        $comment = Comment::factory()->create();

        $response = $this->delete(route('videos.comments.destroy', [$comment->video_id, $comment->id]));

        $response->assertRedirect(route('login'));
    });

    it('should not delete a comment if the user is not the owner', function () {
        $user = User::factory()->create();
        $video = Video::factory()->create();
        $comment = Comment::factory()->create([
            'video_id' => $video->id,
        ]);

        $response = $this->actingAs($user)
            ->delete(route('videos.comments.destroy', [$comment->video_id, $comment->id]));

        $response->assertStatus(403);
    });

    it('should not delete a comment if the comment does not exist', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('videos.comments.destroy', [1, 1]));

        $response->assertStatus(404);
    });
});
