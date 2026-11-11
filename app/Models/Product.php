<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable([
    'name', 'slug', 'short_description', 'description', 'specifications', 'pk_capacity',
    'price_label', 'wa_message', 'image_position', 'is_available', 'is_published', 'sort_order',
])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'specifications' => 'array',
            'pk_capacity' => 'decimal:1',
            'is_available' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

   public function media(): BelongsToMany
{
    return $this->belongsToMany(Media::class, 'product_media', 'product_id', 'media_id')
        ->withPivot(['is_cover', 'sort_order'])
        ->withTimestamps()
        ->orderByPivot('sort_order');
}

    public function cover(): ?Media
    {
        return $this->media->first();
    }

    /**
     * Map the stored image position hint to the literal Tailwind classes used
     * by the frontend template. Classes stay in the template so the compiled
     * stylesheet always contains them.
     */
    public function imagePositionClass(): string
    {
        return match ($this->image_position) {
            'center_35' => 'object-cover object-[center_35%]',
            'center_40' => 'object-cover object-[center_40%]',
            default => 'object-cover',
        };
    }
}
