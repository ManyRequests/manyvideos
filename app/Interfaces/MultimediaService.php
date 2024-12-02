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

    /**
     * Generates a thumbnail from a video and return the new file path
     *
     * @param string $video
     * @return string
     */
    public function generateVideoThumbnail(string $filepath): string;

    /**
     *  Get the metadata of a video
     *
     * @param string $video
     * @return array
     */
    public function getVideoMetadata(string $filepath): array;
}
