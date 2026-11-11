<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'slug' => fake()->unique()->slug(3),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'specifications' => ['type' => fake()->word()],
            'is_available' => true,
            'is_published' => true,
            'sort_order' => 0,
        ];
    }
}
