<?php

use App\Models\Video;
use App\Notifications\VideoProcessingFailedNotification;
use Illuminate\Support\Facades\Notification;

describe('Video Processing Failed Notification', function () {
    it('receives a Video Model', function () {
        $video = Video::factory()->create();

        $notification = new VideoProcessingFailedNotification($video);

        expect($notification->video->id)->toBe($video->id);
    });

    it('sends a notification', function () {
        Notification::fake();

        $video = Video::factory()->create();

        $video->user->notify(new VideoProcessingFailedNotification($video));

        Notification::assertSentTo($video->user, VideoProcessingFailedNotification::class);
    });

    it('sends a notification via mail, database and broadcast', function () {
        Notification::fake();

        $video = Video::factory()->create();

        $video->user->notify(new VideoProcessingFailedNotification($video));
        // this notification is queued, so we need to wait for the job to finish

        Notification::assertSentTo($video->user, VideoProcessingFailedNotification::class, function ($notification, $channels) {
            return in_array('mail', $channels) && in_array('database', $channels) && in_array('broadcast', $channels);
        });
    });

    it('return correctly the array representation', function () {
        $video = Video::factory()->create();

        $notification = new VideoProcessingFailedNotification($video);

        expect($notification->toArray($video->user))->toBe([
            'user_id' => $video->user->id,
            'video_id' => $video->id,
            'video_title' => $video->title,
        ]);
    });

    it('return correctly the mail representation', function () {
        $video = Video::factory()->create();

        $notification = new VideoProcessingFailedNotification($video);

        // should be a message like "Your video 'Video Title' has failed to process!"
        expect($notification->toMail($video->user)->subject)->toBe('Your video has failed to process!');
        expect($notification->toMail($video->user)->introLines)->toBe(["Your video '{$video->title}' has failed to process!"]);
    });
});
