<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PageSection>
 */
class PageSectionFactory extends Factory
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
            'type' => 'rich_text',
            'section_key' => fake()->unique()->slug(2),
            'content' => ['heading' => fake()->sentence(3), 'body' => fake()->paragraph()],
            'is_published' => true,
            'sort_order' => 0,
        ];
    }
}
