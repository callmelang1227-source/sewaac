<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\MediaAttachment;
use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MediaAttachment>
 */
class MediaAttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'media_id' => Media::factory(),
            'attachable_type' => Page::class,
            'attachable_id' => Page::factory(),
            'slot' => 'default',
            'sort_order' => 0,
        ];
    }
}
