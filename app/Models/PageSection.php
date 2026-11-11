<?php

namespace App\Models;

use Database\Factories\PageSectionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['page_id', 'type', 'section_key', 'content', 'is_published', 'sort_order'])]
class PageSection extends Model
{
    /** @use HasFactory<PageSectionFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'is_published' => 'boolean',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
