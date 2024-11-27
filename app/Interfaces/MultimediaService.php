<?php
namespace App\Interfaces;

use App\Models\Video;

interface MultimediaService
{

    /**
     * Compresses a video and return the new file path
     *
     * @param string $video
     * @return string
     */
    public function compressVideo(string $filepath): string;
}
