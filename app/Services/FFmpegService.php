<?php
namespace App\Services;

use App\Interfaces\MultimediaService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class FFmpegService implements MultimediaService
{
    public function compressVideo(string $filepath): string
    {
        $filepath_extension = pathinfo($filepath, PATHINFO_EXTENSION);
        $compressed_filepath = str_replace(".{$filepath_extension}", '-compressed.mp4', $filepath);

        FFMpeg::fromFilesystem(Storage::disk('public'))
            ->open($filepath)
            ->export()
            ->toDisk(Storage::disk('public'))
            ->inFormat(new \FFMpeg\Format\Video\X264('libmp3lame', 'libx264'))
            ->save($compressed_filepath);

        return $compressed_filepath;
    }

    public function generateVideoThumbnail(string $filepath): string
    {
        $filepath_extension = pathinfo($filepath, PATHINFO_EXTENSION);
        $thumbnail_filepath = str_replace(".{$filepath_extension}", '-thumbnail.jpg', $filepath);

        FFMpeg::fromFilesystem(Storage::disk('public'))
            ->open($filepath)
            ->getFrameFromSeconds(5)
            ->export()
            ->toDisk(Storage::disk('public'))
            ->save($thumbnail_filepath);

        return $thumbnail_filepath;
    }

    public function getVideoMetadata(string $filepath): array
    {
        // we need width, height, duration, and size
        if (!Storage::disk('public')->exists($filepath)) {
            return [];
        }

        $metadata = FFMpeg::fromFilesystem(Storage::disk('public'))
            ->open($filepath)
            ->getStreams();

        $metadata = Arr::first($metadata)->all();
        $size = Storage::disk('public')->size($filepath);

        return [
            'width' => $metadata['width'],
            'height' => $metadata['height'],
            'duration' => $metadata['duration'],
            'size' => $size,
        ];
    }
}
