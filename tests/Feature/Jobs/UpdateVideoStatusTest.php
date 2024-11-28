<?php
use App\Jobs\UpdateVideoStatus;
use App\Models\Video;
use App\Enums\VideoStatusEnum;

it('updates the video status', function () {
    $video = Video::factory()->create();

    UpdateVideoStatus::dispatch($video, VideoStatusEnum::Processed);

    $this->assertEquals(VideoStatusEnum::Processed, $video->refresh()->status);
});
