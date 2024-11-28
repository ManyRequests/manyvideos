<?php

namespace App\Jobs;

use App\Interfaces\MultimediaService;
use App\Models\Video;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessVideo implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Video $video
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(MultimediaService $multimediaService): void
    {
        try {
            $compressed_path = $multimediaService->compressVideo($this->video->url);

            $this->video->update([
                'url' => $compressed_path,
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }
}
