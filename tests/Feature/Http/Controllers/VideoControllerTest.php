<?php

use App\Interfaces\MultimediaService;
use App\Jobs\GenerateVideoThumbnail;
use App\Jobs\ProcessVideo;
use App\Jobs\SaveVideoMetadata;
use App\Jobs\SendVideoProcessingCompletedNotification;
use App\Jobs\UpdateVideoStatus;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

describe('VideoController', function () {

    beforeEach(function () {
        $this->user = User::factory()->create();

        $this->mock(MultimediaService::class, function ($mock) {
            $mock->shouldReceive('compressVideo')
                ->andReturn('videos/compressed.mp4');

            $mock->shouldReceive('generateVideoThumbnail')
                ->andReturn('thumbnails/default.jpg');

            $mock->shouldReceive('getVideoMetadata')
                ->andReturn([
                    'size' => 1048576,
                    'duration' => 60,
                    'width' => 1920,
                    'height' => 1080,
                ]);
        });

        Notification::fake();
    });

    it('lists videos', function () {
        $video = Video::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $this->actingAs($this->user);

        $response = $this->get('/videos')
            ->assertStatus(200);

        $response->assertSee($video->title);
    });

    it('lists only the user videos', function () {
        $video = Video::factory()->create();
        $this->actingAs($this->user);

        $response = $this->get('/videos')
            ->assertStatus(200);

        $response->assertDontSee($video->title);
    });

    it('creates a video', function () {
        $this->actingAs($this->user);

        $file = UploadedFile::fake()->create('video.mp4', 1024, 'video/mp4');

        $response = $this->post('/videos', [
            'title' => 'My video',
            'description' => 'My video description',
            'file' => $file,
        ]);

        // redirects to the video list
        $response->assertRedirect('/videos');

        $this->assertDatabaseHas('videos', [
            'title' => 'My video',
            'url' => 'videos/compressed.mp4',
            'description' => 'My video description',
            'size' => 1048576,
            'duration' => 60,
            'user_id' => $this->user->id,
        ]);
    });

    it('stores the video file', function () {
        $this->actingAs($this->user);

        $file = UploadedFile::fake()->create('video.mp4', 1024, 'video/mp4');

        Storage::fake('public');

        $response = $this->post('/videos', [
            'title' => 'My video',
            'description' => 'My video description',
            'file' => $file,
        ]);

        Storage::disk('public')->assertExists('videos/' . $file->hashName());
    });

    it('calls the video process jobs', function () {
        $this->actingAs($this->user);
        Bus::fake();

        $file = UploadedFile::fake()->create('video.mp4', 1024, 'video/mp4');

        $response = $this->post('/videos', [
            'title' => 'My video',
            'description' => 'My video description',
            'file' => $file,
        ]);

        Bus::assertChained([
            ProcessVideo::class,
            GenerateVideoThumbnail::class,
            SaveVideoMetadata::class,
            UpdateVideoStatus::class,
            SendVideoProcessingCompletedNotification::class,
        ]);
    });
});
