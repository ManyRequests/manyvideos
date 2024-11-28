<?php
namespace App\Services;

use App\Interfaces\MultimediaService;
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
}
