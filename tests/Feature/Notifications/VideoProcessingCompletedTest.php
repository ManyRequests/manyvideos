<?php

use App\Models\Video;
use App\Notifications\VideoProcessingCompleted;
use Illuminate\Support\Facades\Notification;

describe('Video Processing Completed Notification', function () {
    it('receives a Video Model', function () {
        $video = Video::factory()->create();

        $notification = new VideoProcessingCompleted($video);

        expect($notification->video->id)->toBe($video->id);
    });

    it('sends a notification', function () {
        Notification::fake();

        $video = Video::factory()->create();

        $video->user->notify(new VideoProcessingCompleted($video));

        Notification::assertSentTo($video->user, VideoProcessingCompleted::class);
    });

    it('sends a notification via mail, database and broadcast', function () {
        Notification::fake();

        $video = Video::factory()->create();

        $video->user->notify(new VideoProcessingCompleted($video));
        // this notification is queued, so we need to wait for the job to finish

        Notification::assertSentTo($video->user, VideoProcessingCompleted::class, function ($notification, $channels) {
            return in_array('mail', $channels) && in_array('database', $channels) && in_array('broadcast', $channels);
        });
    });

    it('return correctly the array representation', function () {
        $video = Video::factory()->create();

        $notification = new VideoProcessingCompleted($video);

        expect($notification->toArray($video->user))->toBe([
            'user_id' => $video->user->id,
            'video_id' => $video->id,
            'video_title' => $video->title,
        ]);
    });

    it('return correctly the mail representation', function () {
        $video = Video::factory()->create();

        $notification = new VideoProcessingCompleted($video);

        // should be a message like "Your video 'Video Title' has been processed!"

        expect($notification->toMail($video->user)->subject)->toBe('Your video has been processed!');
        expect($notification->toMail($video->user)->introLines)->toBe(["Your video '{$video->title}' has been processed!"]);
    });
});
