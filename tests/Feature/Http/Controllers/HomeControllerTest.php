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
        ->has('videos')
        ->has('videos.data', 3, fn (AssertableInertia $page) => $page
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
        ->has('videos')
        ->has('videos.data', 0)
    );
});

it('renders paginated videos', function () {
    $user = User::factory()->create();
    $videos = Video::factory()->count(10)->create([
        'user_id' => $user->id,
        'status' => VideoStatusEnum::Processed,
    ]);

    $response = $this
        ->get(route('home', [
            'perpage' => 5,
        ]));

    $response->assertInertia(fn (AssertableInertia $page) => $page
        ->component('Home')
        ->has('videos')
        ->where('videos.current_page', 1)
        ->has('videos.data', 5, fn (AssertableInertia $page) => $page
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

    $response = $this
        ->get(route('home', [
            'perpage' => 5,
            'page' => 2,
        ]));

    $response->assertInertia(fn (AssertableInertia $page) => $page
        ->component('Home')
        ->has('videos')
        ->where('videos.current_page', 2)
        ->has('videos.data', 5, fn (AssertableInertia $page) => $page
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
