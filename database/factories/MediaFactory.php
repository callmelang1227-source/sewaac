<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uploaded_by' => User::factory(),
            'disk' => 'public',
            'path' => 'media/'.fake()->unique()->uuid().'.webp',
            'original_filename' => 'photo.webp',
            'filename' => fake()->uuid().'.webp',
            'mime_type' => 'image/webp',
            'extension' => 'webp',
            'type' => 'image',
            'size' => 1024,
            'width' => 800,
            'height' => 600,
            'alt_text' => fake()->sentence(4),
        ];
    }
}
