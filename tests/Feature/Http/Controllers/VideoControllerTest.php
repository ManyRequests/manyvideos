<?php

use App\Models\User;
use App\Models\Video;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

describe('VideoController', function () {

    beforeEach(function () {
        $this->user = User::factory()->create();
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
            'url' => 'videos/' . $file->hashName(),
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
});
