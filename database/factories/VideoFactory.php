<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Video>
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
            'page_id' => Page::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->sentence(),
            'source_type' => 'youtube',
            'external_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'is_published' => true,
            'sort_order' => 0,
        ];
    }
}
