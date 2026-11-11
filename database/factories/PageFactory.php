<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'slug' => fake()->unique()->slug(3),
            'template' => 'service',
            'navigation_label' => fake()->word(),
            'meta_title' => fake()->sentence(4),
            'meta_description' => fake()->paragraph(),
            'meta_robots' => 'index,follow,max-image-preview:large',
            'is_published' => true,
            'published_at' => now(),
            'sort_order' => 0,
        ];
    }
}
