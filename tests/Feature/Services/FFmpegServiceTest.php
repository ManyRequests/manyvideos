<?php

use App\Services\FFmpegService;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

describe('FFmpegService', function () {
    it('should compress a video', function () {
        $service = new FFmpegService();

        $file = new File(resource_path('tests/test_video.mp4'));
        // videos at public disk
        $storage = Storage::disk('public');

        $storage->putFileAs('videos', $file, 'video.mp4');

        $filepath = 'videos/video.mp4';

        $compressed_filepath = $service->compressVideo($filepath);

        $expected_compressed_filepath = 'videos/video-compressed.mp4';
        expect($compressed_filepath)->toBe($expected_compressed_filepath);

        $originalSize = $file->getSize();
        $compressedSize = $storage->size($compressed_filepath);

        expect($storage->exists($compressed_filepath))->toBeTrue();
        expect($compressedSize)->toBeLessThan($originalSize);
        expect($compressedSize)->toBeGreaterThan(0);

        $storage->delete($filepath);
        $storage->delete($compressed_filepath);
    });
});
