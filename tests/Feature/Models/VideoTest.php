<?php
use App\Models\Video;

it('can be created', function () {
    $video = Video::factory()->create();
    expect($video->id)->toBeInt();
});

it('can load comments count if specified', function () {
    $video = Video::factory()->hasComments(3)->create();

    $video = Video::withCommentsCount()->find($video->id);

    expect($video->comments_count)->toBe(3);
});

it('does not load comments count by default', function () {
    $video = Video::factory()->hasComments(3)->create();

    $video = Video::find($video->id);

    expect($video->comments_count)->toBeNull();
});

it('can load with minimal attributes', function () {
    $video = Video::factory()->create();

    $video = Video::withMinAttributes()->find($video->id);

    expect($video->getAttributes())
        ->toHaveKeys(['id', 'title', 'thumbnail', 'status', 'user_id', 'duration', 'created_at']);
});
