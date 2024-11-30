<?php

namespace App\Jobs;

use App\Interfaces\MultimediaService;
use App\Models\Video;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SaveVideoMetadata implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $backoff = 10;

    /**
     * Create a new job instance.
     */
    public function __construct(public Video $video)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(MultimediaService $multimediaService): void
    {
        $metadata = $multimediaService->getVideoMetadata($this->video->url);

        $this->video->update([
            'duration' => $metadata['duration'],
            'size' => $metadata['size'],
            'width' => $metadata['width'],
            'height' => $metadata['height'],
        ]);
    }
}
