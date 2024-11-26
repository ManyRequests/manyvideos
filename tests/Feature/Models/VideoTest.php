<?php
use App\Models\Video;

it('can be created', function () {
    $video = Video::factory()->create();
    expect($video->id)->toBeInt();
});
