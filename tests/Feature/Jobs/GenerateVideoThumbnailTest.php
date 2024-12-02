<?php

use App\Jobs\GenerateVideoThumbnail;
use App\Models\Video;
use App\Services\FFmpegService;

describe('GenerateVideoThumbnailTest', function () {
    it('recieves a Video model', function () {
        $video = Video::factory()->create();
        $job = new GenerateVideoThumbnail($video);

        expect($job->video)->toBe($video);
    });

    it('calls FFmpegService to generate a thumbnail', function () {
        $ffmpegService = Mockery::mock(FFmpegService::class, function ($mock) {
            $mock->shouldReceive('generateVideoThumbnail')->once();
        });
        $video = Video::factory()->create();

        $job = new GenerateVideoThumbnail($video);
        $job->handle($ffmpegService);
    });

    it('sets the thumbnail url on the video model', function () {
        $ffmpegService = Mockery::mock(FFmpegService::class, function ($mock) {
            $mock->shouldReceive('generateVideoThumbnail')->once()->andReturn('videos/video-thumbnail.jpg');
        });
        $video = Video::factory()->create();

        $job = new GenerateVideoThumbnail($video);
        $job->handle($ffmpegService);

        expect($video->thumbnail)->toBe('videos/video-thumbnail.jpg');
    });
});
