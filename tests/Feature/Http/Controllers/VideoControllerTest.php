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
});
