<?php

use App\Models\Comment;
use App\Models\User;
use App\Models\Video;

it('can create a comment', function () {
    $user = User::factory()->create();
    $video = Video::factory()->create();

    $comment = Comment::create([
        'content' => 'This is a comment',
        'user_id' => $user->id,
        'video_id' => $video->id,
    ]);

    expect($comment->id)->toBe(1);
    expect($comment->user_id)->toBe($user->id);
    expect($comment->video_id)->toBe($video->id);
    expect($comment->content)->toBe('This is a comment');
});
