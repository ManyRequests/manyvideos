<?php

namespace App\Jobs;

use App\Interfaces\MultimediaService;
use App\Models\Video;
use App\Services\FFmpegService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateVideoThumbnail implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $backoff = 10;

    /**
     * Create a new job instance.
     */
    public function __construct(public Video $video)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(MultimediaService $multimediaService): void
    {
        $thumbnail_path = $multimediaService->generateVideoThumbnail($this->video->url);

        $this->video->update([
            'thumbnail' => $thumbnail_path,
        ]);
    }
}
