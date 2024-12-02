<?php
use App\Models\Tag;
use App\Models\User;

beforeEach(function () {
    $user = User::factory()->create();

    $this->actingAs($user);
});

describe('tag store', function () {

    it('should store a new tag', function () {
        $response = $this->post(route('tags.store'), [
            'name' => 'Laravel',
            'color' => '#FF0000',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('tags', [
            'name' => 'Laravel',
            'color' => '#FF0000',
        ]);
    });

    it('should not store a new tag with the same name', function () {
        $tag = Tag::factory()->create();

        $response = $this->post(route('tags.store'), [
            'name' => $tag->name,
            'color' => '#FF0000',
        ]);

        $response->assertStatus(302);

        $count = Tag::where('name', $tag->name)->count();
        $this->assertEquals(1, $count);
    });
});

describe('tag update', function () {
    it('should update a tag', function () {
        $tag = Tag::factory()->create();

        $response = $this->put(route('tags.update', $tag), [
            'name' => 'Laravel',
            'color' => '#FF0000',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tags', [
            'name' => 'Laravel',
            'color' => '#FF0000',
        ]);
    });

    it('should not update a tag with the same name', function () {
        $tag1 = Tag::factory()->create();
        $tag2 = Tag::factory()->create();

        $response = $this->put(route('tags.update', $tag1), [
            'name' => $tag2->name,
            'color' => '#FF0000',
        ]);

        $response->assertStatus(302);
    });
});

describe('tag delete', function () {
    it('should delete a tag', function () {
        $tag = Tag::factory()->create();

        $response = $this->delete(route('tags.destroy', $tag));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tags', [
            'name' => $tag->name,
            'color' => $tag->color,
        ]);
    });
});
