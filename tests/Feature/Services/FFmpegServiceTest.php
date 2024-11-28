<?php

use App\Services\FFmpegService;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

describe('FFmpegService', function () {
    describe('compressVideo', function () {
        it('compressVideo should compress a video', function () {
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

    describe('generateVideoThumbnail', function () {
        it('generateVideoThumbnail should generate a thumbnail from a video', function () {
            $service = new FFmpegService();

            $file = new File(resource_path('tests/test_video.mp4'));
            // videos at public disk
            $storage = Storage::disk('public');

            $storage->putFileAs('videos', $file, 'video.mp4');

            $filepath = 'videos/video.mp4';

            $thumbnail_filepath = $service->generateVideoThumbnail($filepath);

            $expected_thumbnail_filepath = 'videos/video-thumbnail.jpg';

            expect($thumbnail_filepath)->toBe($expected_thumbnail_filepath);
            expect($storage->exists($thumbnail_filepath))->toBeTrue();

            $storage->delete($filepath);
            $storage->delete($thumbnail_filepath);
        });
    });

    describe('getVideoMetadata', function () {
        it('getVideoMetadata should return the metadata of a video', function () {
            $service = new FFmpegService();

            $file = new File(resource_path('tests/test_video.mp4'));
            // videos at public disk
            $storage = Storage::disk('public');

            $storage->putFileAs('videos', $file, 'video.mp4');

            $filepath = 'videos/video.mp4';

            $metadata = $service->getVideoMetadata($filepath);

            expect($metadata)->toBeArray();
            expect($metadata)->toHaveKey('duration');
            expect($metadata)->toHaveKey('width');
            expect($metadata)->toHaveKey('height');
            expect($metadata)->toHaveKey('size');

            $storage->delete($filepath);
        });
    });
});
