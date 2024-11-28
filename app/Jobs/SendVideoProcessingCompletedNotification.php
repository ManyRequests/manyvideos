<?php

namespace App\Jobs;

use App\Models\Video;
use App\Notifications\VideoProcessingCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendVideoProcessingCompletedNotification implements ShouldQueue
{
    use Queueable;

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
    public function handle(): void
    {
        $this->video->load('user');

        $this->video->user->notify(new VideoProcessingCompleted($this->video));
    }
}
