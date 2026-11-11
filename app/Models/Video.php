<?php

namespace App\Models;

use Database\Factories\VideoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'page_id', 'media_id', 'thumbnail_media_id', 'title', 'description', 'source_type',
    'external_url', 'is_published', 'sort_order',
])]
class Video extends Model
{
    /** @use HasFactory<VideoFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function thumbnailMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'thumbnail_media_id');
    }

    public function isYouTube(): bool
    {
        return $this->source_type === 'youtube';
    }

    public function isLocal(): bool
    {
        return $this->source_type === 'local';
    }

    public function youtubeId(): ?string
    {
        if (! $this->isYouTube() || $this->external_url === null) {
            return null;
        }

        return \App\Services\Cms\YouTubeUrl::extractVideoId($this->external_url);
    }

    public function embedUrl(): ?string
    {
        $videoId = $this->youtubeId();

        return $videoId === null ? null : 'https://www.youtube-nocookie.com/embed/'.$videoId;
    }

    public function sourceUrl(): ?string
    {
        if ($this->isLocal()) {
            return $this->media?->url();
        }

        return $this->embedUrl();
    }

    public function posterUrl(): ?string
    {
        return $this->thumbnailMedia?->url();
    }
}
