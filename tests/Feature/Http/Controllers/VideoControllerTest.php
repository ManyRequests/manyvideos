<?php

use App\Enums\VideoStatusEnum;
use App\Interfaces\MultimediaService;
use App\Jobs\GenerateVideoThumbnail;
use App\Jobs\ProcessVideo;
use App\Jobs\SaveVideoMetadata;
use App\Jobs\SendVideoProcessingCompletedNotification;
use App\Jobs\UpdateVideoStatus;
use App\Models\Tag;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Testing\AssertableInertia;

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

    describe('show endpoint', function () {
        it('shows a video', function () {
            $video = Video::factory()->create([
                'user_id' => $this->user->id,
                'status' => VideoStatusEnum::Processed,
            ]);
            $this->actingAs($this->user);

            $response = $this->get('/videos/' . $video->id)
                ->assertStatus(200);

            $response->assertInertia(function (AssertableInertia $page) use ($video) {
                $page->where('video.id', $video->id);
                $page->where('video.title', $video->title);
                $page->where('video.description', $video->description);
                $page->where('video.url', $video->url);
            });
        });

        it('show a video as a non owner', function () {
            $video = Video::factory()->create([
                'status' => VideoStatusEnum::Processed,
            ]);
            $this->actingAs($this->user);

            $response = $this->get('/videos/' . $video->id)
                ->assertStatus(200);
        });

        it('renders with the video user', function () {
            $video = Video::factory()->create([
                'user_id' => $this->user->id,
                'status' => VideoStatusEnum::Processed,
            ]);
            $this->actingAs($this->user);

            $response = $this->get('/videos/' . $video->id)
                ->assertStatus(200)
                ->assertInertia(function (AssertableInertia $page) {
                    $page->where('video.user.id', $this->user->id);
                });
        });

        it('renders the video tags', function () {
            $video = Video::factory()->create([
                'user_id' => $this->user->id,
                'status' => VideoStatusEnum::Processed,
            ]);

            $tag = Tag::factory()->create();
            $video->tags()->attach($tag->id);

            $this->actingAs($this->user);

            $response = $this->get('/videos/' . $video->id)
                ->assertStatus(200);

            $response->assertInertia(function (AssertableInertia $page) use ($tag) {
                $page->where('video.tags.0.name', $tag->name);
            });
        });

        it('renders with comments', function () {
            $video = Video::factory()->create([
                'user_id' => $this->user->id,
                'status' => VideoStatusEnum::Processed,
            ]);

            $video->comments()->create([
                'user_id' => $this->user->id,
                'content' => 'My comment',
            ]);

            $this->actingAs($this->user);

            $response = $this->get('/videos/' . $video->id)
                ->assertStatus(200)
                ->assertInertia(function (AssertableInertia $page) {
                    $page->where('video.comments.0.content', 'My comment');
                });
        });

        it('doesnt allow to see a video in processing status', function () {
            $video = Video::factory()->create([
                'user_id' => $this->user->id,
                'status' => VideoStatusEnum::Processing,
            ]);
            $this->actingAs($this->user);

            $response = $this->get(route('videos.show', $video))
                ->assertStatus(403);
        });
    });

    describe('index endpoint', function () {
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

        it('lists videos with tags', function () {
            $video = Video::factory()->create([
                'user_id' => $this->user->id,
            ]);

            $tag = Tag::factory()->create();
            $video->tags()->attach($tag->id);

            $this->actingAs($this->user);

            $response = $this->get('/videos')
                ->assertStatus(200);

            $response->assertSee($tag->name);
        });

        it('lists videos with comment count', function () {
            $video = Video::factory()->create([
                'user_id' => $this->user->id,
            ]);

            $video->comments()->create([
                'user_id' => $this->user->id,
                'content' => 'My comment',
            ]);

            $this->actingAs($this->user);

            $response = $this->get('/videos')
                ->assertStatus(200)
                ->assertInertia(function (AssertableInertia $page) {
                    $page->count('videos', 1);
                    $page->where('videos.0.comments_count', 1);
                });


        });
    });

    describe('create endpoint', function () {
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

        it('creates a video with tags', function () {
            $this->actingAs($this->user);

            $file = UploadedFile::fake()->create('video.mp4', 1024, 'video/mp4');

            $response = $this->post('/videos', [
                'title' => 'My video',
                'description' => 'My video description',
                'file' => $file,
                'tags' => ['Gaming', 'Music'],
            ]);

            $video = Video::where('title', 'My video')->first();

            // created  tags
            $this->assertDatabaseHas('tags', [
                'name' => 'gaming',
            ]);

            $this->assertDatabaseHas('tags', [
                'name' => 'music',
            ]);

            $tags = Tag::whereIn('name', ['gaming', 'music'])->get();

            $this->assertDatabaseHas('video_tag', [
                'tag_id' =>  $tags[0]->id,
                'video_id' => $video->id,
            ]);

            $this->assertDatabaseHas('video_tag', [
                'tag_id' => $tags[1]->id,
                'video_id' => $video->id,
            ]);
        });
    });

    describe('update endpoint', function () {
        it('updates a video', function () {
            $video = Video::factory()->create([
                'user_id' => $this->user->id,
            ]);
            $this->actingAs($this->user);

            $response = $this->put('/videos/' . $video->id, [
                'title' => 'My updated video',
                'description' => 'My updated video description',
            ]);

            $response->assertRedirect('/videos');

            $this->assertDatabaseHas('videos', [
                'id' => $video->id,
                'title' => 'My updated video',
                'description' => 'My updated video description',
            ]);
        });

        it('updates a video with a new file', function () {
            $video = Video::factory()->create([
                'user_id' => $this->user->id,
            ]);
            $this->actingAs($this->user);

            $file = UploadedFile::fake()->create('video.mp4', 1024, 'video/mp4');

            Storage::fake('public');

            $response = $this->put('/videos/' . $video->id, [
                'title' => 'My updated video',
                'description' => 'My updated video description',
                'file' => $file,
            ]);

            Storage::disk('public')->assertExists('videos/' . $file->hashName());
        });

        it('calls the video process jobs when updating the file', function () {
            $video = Video::factory()->create([
                'user_id' => $this->user->id,
            ]);
            $this->actingAs($this->user);
            Bus::fake();

            $file = UploadedFile::fake()->create('video.mp4', 1024, 'video/mp4');

            $response = $this->put('/videos/' . $video->id, [
                'title' => 'My updated video',
                'description' => 'My updated video description',
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

        it('updates a video with new tags', function () {
            $video = Video::factory()->create([
                'user_id' => $this->user->id,
            ]);
            $this->actingAs($this->user);

            $tags = Tag::factory(2)->create();

            $video->tags()->sync($tags->pluck('id'));

            $response = $this->put('/videos/' . $video->id, [
                'title' => 'My updated video',
                'description' => 'My updated video description',
                'tags' => [
                    'Gaming',
                    'Music',
                ],
            ]);

            $this->assertDatabaseHas('tags', [
                'name' => 'Gaming',
            ]);

            $this->assertDatabaseHas('tags', [
                'name' => 'Music',
            ]);

            $tags = Tag::whereIn('name', ['Gaming', 'Music'])->get();

            $this->assertDatabaseHas('video_tag', [
                'tag_id' =>  $tags[0]->id,
                'video_id' => $video->id,
            ]);

            $this->assertDatabaseHas('video_tag', [
                'tag_id' => $tags[1]->id,
                'video_id' => $video->id,
            ]);
        });

        it('doesnt updates other user videos', function () {
            $video = Video::factory()->create();
            $this->actingAs($this->user);

            $response = $this->put('/videos/' . $video->id, [
                'title' => 'My updated video',
                'description' => 'My updated video description',
            ]);

            $response->assertStatus(403);
        });
    });

    describe('delete endpoint', function () {
        it('deletes a video', function () {
            $video = Video::factory()->create([
                'user_id' => $this->user->id,
            ]);
            $this->actingAs($this->user);

            $response = $this->delete('/videos/' . $video->id);

            $response->assertRedirect('/videos');

            $this->assertDatabaseMissing('videos', [
                'id' => $video->id,
            ]);
        });

        it('deletes the video file', function () {
            $video = Video::factory()->create([
                'user_id' => $this->user->id,
            ]);
            $this->actingAs($this->user);

            $storage = Storage::fake('public');
            $storage->put($video->url, 'video content');
            $storage->put($video->thumbnail, 'thumbnail content');

            $response = $this->delete('/videos/' . $video->id);

            Storage::disk('public')->assertMissing($video->url);
            Storage::disk('public')->assertMissing($video->thumbnail);
        });

        it('cant delete other user videos', function () {
            $video = Video::factory()->create();
            $this->actingAs($this->user);

            $response = $this->delete('/videos/' . $video->id);

            $response->assertStatus(403);
        });
    });
});
