<?php

namespace App\Models;

use Database\Factories\SiteSettingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['group_name', 'key', 'value'])]
class SiteSetting extends Model
{
    /** @use HasFactory<SiteSettingFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'value' => 'array',
        ];
    }
}
