<?php

use App\Jobs\SendVideoProcessingCompletedNotification;
use App\Notifications\VideoProcessingCompleted;
use App\Models\Video;
use Illuminate\Support\Facades\Bus;

it('receives a video instance', function () {
    $video = Video::factory()->create();

    $job = new SendVideoProcessingCompletedNotification($video);

    expect($job->video->id)->toBe($video->id);
});
