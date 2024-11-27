<?php

use App\Models\User;
use App\Models\Video;

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

        $response = $this->post('/videos', [
            'title' => 'My video',
            'description' => 'My video description',
            'url' => 'https://www.youtube.com/watch?v=12345',
            'thumbnail' => 'https://www.example.com/thumbnail.jpg',
            'size' => 1024,
            'duration' => 60,
        ]);

        // redirects to the video list
        $response->assertRedirect('/videos');

        $this->assertDatabaseHas('videos', [
            'title' => 'My video',
            'description' => 'My video description',
            'url' => 'https://www.youtube.com/watch?v=12345',
            'thumbnail' => 'https://www.example.com/thumbnail.jpg',
            'size' => 1024,
            'duration' => 60,
            'user_id' => $this->user->id,
        ]);
    });
});
