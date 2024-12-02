<?php

use App\Models\Tag;

it('can create a tag', function () {
    $tag = Tag::factory()->create();

    expect($tag->name)->toBeString();
    expect($tag->color)->toBeString();
});
