<?php

namespace App\Models;

use Database\Factories\MediaAttachmentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable(['media_id', 'attachable_type', 'attachable_id', 'slot', 'sort_order'])]
class MediaAttachment extends Model
{
    /** @use HasFactory<MediaAttachmentFactory> */
    use HasFactory;

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }
}
