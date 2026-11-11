<?php

namespace App\Models;

use Database\Factories\MediaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'uploaded_by', 'disk', 'path', 'original_filename', 'filename', 'mime_type',
    'extension', 'type', 'size', 'width', 'height', 'alt_text',
])]
class Media extends Model
{
    /** @use HasFactory<MediaFactory> */
    use HasFactory;

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(MediaAttachment::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(['is_cover', 'sort_order'])
            ->withTimestamps();
    }

    public function isInUse(): bool
    {
        return $this->attachments()->exists()
            || $this->products()->exists()
            || Video::query()->where('media_id', $this->id)->orWhere('thumbnail_media_id', $this->id)->exists()
            || $this->isReferencedBySettings();
    }

    /**
     * Whether a site setting (logo, Open Graph image, ...) points at this media.
     */
    public function isReferencedBySettings(): bool
    {
        foreach (app(SiteSettings::class)->all() as $key => $value) {
            if (str_ends_with((string) $key, 'media_id') && (int) $value === $this->id) {
                return true;
            }
        }

        return false;
    }

    public function url(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function absoluteUrl(): string
    {
        $url = $this->url();

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        return rtrim(config('seo.site_url'), '/').$url;
    }

    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    public function isVideo(): bool
    {
        return $this->type === 'video';
    }

    public function humanSize(): string
    {
        if ($this->size >= 1024 * 1024) {
            return round($this->size / (1024 * 1024), 1).' MB';
        }

        return max(1, (int) round($this->size / 1024)).' KB';
    }
}
