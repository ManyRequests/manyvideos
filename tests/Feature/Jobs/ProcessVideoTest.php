<?php

use App\Enums\VideoStatusEnum;
use App\Interfaces\MultimediaService;
use App\Jobs\ProcessVideo;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;

describe('ProcessVideo Job Tests', function () {
    it('should compress the video', function () {
        Storage::fake('public');

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

    it('sets video status to failed if compression fails', function () {
        Storage::fake('public');

        $video = Video::factory()->create([
            'url' => 'videos/video.mp4',
        ]);

        $job = new ProcessVideo($video);
        $job->failed(new Exception('Failed to compress video'));

        expect($video->status)->toBe(VideoStatusEnum::Failed);
    });

    it('removes the video from the storage if compression fails', function () {
        $storage = Storage::fake('public');

        $video = Video::factory()->create([
            'url' => 'videos/video.mp4',
        ]);

        $storage->put('videos/video.mp4', 'video content');

        $job = (new ProcessVideo($video))->withFakeQueueInteractions();
        $job->failed(new Exception('Failed to compress video'));

        $storage->assertMissing('videos/video.mp4');
    });
});
