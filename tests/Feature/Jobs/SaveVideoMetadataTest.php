<?php

use App\Interfaces\MultimediaService;
use App\Jobs\SaveVideoMetadata;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;

describe('SaveVideoMetadata Job Tests', function () {
    it('should save the video metadata', function () {
        Storage::fake('public');

        $video = Video::factory()->create([
            'url' => 'videos/video.mp4',
        ]);

        $multimediaService = mock(MultimediaService::class);
        $multimediaService->shouldReceive('getVideoMetadata')
            ->once()
            ->with($video->url)
            ->andReturn([
                'duration' => 60,
                'size' => 1024,
                'width' => 1920,
                'height' => 1080,
            ]);

        $job = new SaveVideoMetadata($video);
        $job->handle($multimediaService);

        $video->refresh();

        expect($video->duration)->toBe(60);
        expect($video->size)->toBe(1024);
        expect($video->width)->toBe(1920);
        expect($video->height)->toBe(1080);
    });
});
