<?php

use App\Interfaces\MultimediaService;
use App\Jobs\ProcessVideo;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;

describe('ProcessVideo Job Tests', function () {
    it('should compress the video', function () {
        Storage::fake('local');

        $video = Video::factory()->create([
            'url' => 'videos/video.mp4',
        ]);

        $multimediaService = mock(MultimediaService::class);
        $multimediaService->shouldReceive('compressVideo')
            ->once()
            ->with($video->url)
            ->andReturn('videos/video-compressed.mp4');

        $job = new ProcessVideo($video);
        $job->handle($multimediaService);

        expect($video->url)->toBe('videos/video-compressed.mp4');
    });
});
