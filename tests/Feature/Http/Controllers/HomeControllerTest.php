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

it('filters by course title', function () {
    $user = User::factory()->create();
    $videos = Video::factory()->count(3)->create([
        'user_id' => $user->id,
        'status' => VideoStatusEnum::Processed,
    ]);

    $response = $this
        ->get(route('home', [
            'search' => $videos[0]->title,
        ]));

    $response->assertInertia(fn (AssertableInertia $page) => $page
        ->component('Home')
        ->has('videos')
        ->has('videos.data', 1, fn (AssertableInertia $page) => $page
            ->has('id')
            ->where('id', $videos[0]->id)
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

describe('filter videos by size', function () {
    it('filters by video size range', function () {
        $user = User::factory()->create();
        $videos = Video::factory(1)->create([
            'user_id' => $user->id,
            'status' => VideoStatusEnum::Processed,
            'size' => 7 * 1024 * 1024,
        ]);

        Video::factory(5)->create([
            'user_id' => $user->id,
            'status' => VideoStatusEnum::Processed,
            'size' => 20 * 1024 * 1024,
        ]);

        $response = $this
            ->get(route('home', [
                'size_min' => '5',
                'size_max' => '10',
            ]));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Home')
            ->has('videos')
            ->has('videos.data', 1, fn (AssertableInertia $page) => $page
                ->has('id')
                ->where('id', $videos[0]->id)
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

    it('filters by video size min', function () {
        $user = User::factory()->create();
        $videos = Video::factory(1)->create([
            'user_id' => $user->id,
            'status' => VideoStatusEnum::Processed,
            'size' => 7 * 1024 * 1024,
        ]);

        Video::factory(5)->create([
            'user_id' => $user->id,
            'status' => VideoStatusEnum::Processed,
            'size' => 20 * 1024 * 1024,
        ]);

        $response = $this
            ->get(route('home', [
                'size_min' => '5',
            ]));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Home')
            ->has('videos')
            ->has('videos.data', 6)
        );
    });

    it('filters by video size max', function () {
        $user = User::factory()->create();
        $videos = Video::factory(1)->create([
            'user_id' => $user->id,
            'status' => VideoStatusEnum::Processed,
            // size is stored in bytes
            'size' => 7 * 1024 * 1024,
        ]);

        Video::factory(5)->create([
            'user_id' => $user->id,
            'status' => VideoStatusEnum::Processed,
            'size' => 20 * 1024 * 1024,
        ]);

        $response = $this
            ->get(route('home', [
                'size_max' => '10',
            ]));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Home')
            ->has('videos')
            ->has('videos.data', 1, fn (AssertableInertia $page) => $page
                ->has('id')
                ->where('id', $videos[0]->id)
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
});

describe('filter videos by duration', function () {
    it('filters by video duration range in minutes', function () {
        $user = User::factory()->create();
        $videos = Video::factory(1)->create([
            'user_id' => $user->id,
            'status' => VideoStatusEnum::Processed,
            'duration' => 7 * 60,
        ]);

        Video::factory(5)->create([
            'user_id' => $user->id,
            'status' => VideoStatusEnum::Processed,
            'duration' => 20 * 60,
        ]);

        $response = $this
            ->get(route('home', [
                'duration_min' => '5',
                'duration_max' => '10',
            ]));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Home')
            ->has('videos')
            ->has('videos.data', 1, fn (AssertableInertia $page) => $page
                ->has('id')
                ->where('id', $videos[0]->id)
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

    it('filters by video duration min in minutes', function () {
        $user = User::factory()->create();
        $videos = Video::factory(1)->create([
            'user_id' => $user->id,
            'status' => VideoStatusEnum::Processed,
            'duration' => 7 * 60,
        ]);

        Video::factory(5)->create([
            'user_id' => $user->id,
            'status' => VideoStatusEnum::Processed,
            'duration' => 20 * 60,
        ]);

        $response = $this
            ->get(route('home', [
                'duration_min' => '5',
            ]));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Home')
            ->has('videos')
            ->has('videos.data', 6)
        );
    });

    it('filters by video duration max in minutes', function () {
        $user = User::factory()->create();
        $videos = Video::factory(1)->create([
            'user_id' => $user->id,
            'status' => VideoStatusEnum::Processed,
            'duration' => 7 * 60,
        ]);

        Video::factory(5)->create([
            'user_id' => $user->id,
            'status' => VideoStatusEnum::Processed,
            'duration' => 20 * 60,
        ]);

        $response = $this
            ->get(route('home', [
                'duration_max' => '10',
            ]));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Home')
            ->has('videos')
            ->has('videos.data', 1, fn (AssertableInertia $page) => $page
                ->has('id')
                ->where('id', $videos[0]->id)
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
});
