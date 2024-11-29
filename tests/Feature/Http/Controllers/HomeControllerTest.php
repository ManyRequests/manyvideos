<?php

use App\Enums\VideoStatusEnum;
use App\Models\User;
use App\Models\Video;
use App\Models\Tag;
use Inertia\Testing\AssertableInertia;

it('renders home correctly', function () {
    $response = $this->get(route('home'));

    $response->assertInertia(function (AssertableInertia $page) {
        $page->component('Home');
        $page->has('canLogin');
        $page->has('canRegister');
    });
});

it('renders with videos correctly', function () {
    $user = User::factory()->create();
    $videos = Video::factory()->count(3)->create([
        'user_id' => $user->id,
        'status' => VideoStatusEnum::Processed,
    ]);

    $response = $this
        ->get(route('home'));

    $response->assertInertia(fn (AssertableInertia $page) => $page
        ->component('Home')
        ->has('videos', 3, fn (AssertableInertia $page) => $page
            ->has('id')
            ->has('title')
            ->has('thumbnail')
            ->has('duration')
            ->has('user_id')
            ->has('created_at')
            ->has('user')
            ->has('tags')
        )
    );
});


it('does not render videos that are not processed', function () {
    $user = User::factory()->create();
    $videos = Video::factory()->count(3)->create([
        'user_id' => $user->id,
        'status' => VideoStatusEnum::Processing,
    ]);

    $response = $this
        ->get(route('home'));

    $response->assertInertia(fn (AssertableInertia $page) => $page
        ->component('Home')
        ->has('videos', 0)
    );
});
