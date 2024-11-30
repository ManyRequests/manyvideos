<?php

namespace App\Jobs;

use App\Enums\VideoStatusEnum;
use App\Interfaces\MultimediaService;
use App\Models\Video;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        sleep(3);
        throw new Exception("Error Processing video");

        $compressed_path = $multimediaService->compressVideo($this->video->url);

        $this->video->update([
            'url' => $compressed_path,
        ]);
    }

    public function failed(\Throwable $exception)
    {
        Log::error($exception->getMessage());

        $this->video->update([
            'status' => VideoStatusEnum::Failed,
        ]);

        Storage::disk('public')->delete($this->video->url);
    }
}
