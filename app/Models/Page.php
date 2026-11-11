<?php

namespace App\Models;

use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'title', 'slug', 'template', 'service_area', 'navigation_label', 'meta_title', 'meta_description',
    'meta_keywords', 'meta_robots', 'canonical_path', 'open_graph_title',
    'open_graph_description', 'is_published', 'published_at', 'sort_order',
])]
class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('sort_order')->orderBy('id');
    }

    public function publishedSections(): HasMany
    {
        return $this->sections()->where('is_published', true);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class)->orderBy('sort_order')->orderBy('id');
    }

    public function publishedVideos(): HasMany
    {
        return $this->videos()->where('is_published', true);
    }

    /**
     * Media attached through the polymorphic attachments table for a named slot.
     */
    public function slotMedia(string $slot): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'media_attachments', 'attachable_id', 'media_id')
            ->where('media_attachments.attachable_type', static::class)
            ->where('media_attachments.slot', $slot)
            ->orderBy('media_attachments.sort_order')
            ->orderBy('media_attachments.id');
    }

    public function areaCardMedia(): BelongsToMany
    {
        return $this->slotMedia('area_card');
    }

    /** @param Builder<Page> $query */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /** @param Builder<Page> $query */
    public function scopeServiceAreas(Builder $query): Builder
    {
        return $query->where('template', 'service_area');
    }

    public function path(): string
    {
        return $this->slug === 'home' ? '/' : '/'.$this->slug;
    }

    /**
     * Absolute canonical URL based on the configured SEO site URL.
     */
    public function url(): string
    {
        return rtrim(config('seo.site_url'), '/').$this->path();
    }
}
