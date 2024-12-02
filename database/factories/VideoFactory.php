<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'url' => $this->faker->url,
            'thumbnail' => $this->faker->imageUrl,
            'size' => $this->faker->randomNumber,
            'duration' => $this->faker->randomNumber,
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
