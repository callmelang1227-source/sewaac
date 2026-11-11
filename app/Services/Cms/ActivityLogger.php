<?php

namespace App\Services\Cms;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActivityLogger
{
    /**
     * Record an administrator action in the activity log.
     *
     * @param  array<string, mixed>  $properties
     */
    public function record(
        string $action,
        ?string $description = null,
        ?Model $subject = null,
        array $properties = [],
        ?User $user = null,
    ): ActivityLog {
        return ActivityLog::query()->create([
            'user_id' => $user?->id ?? auth()->id(),
            'action' => $action,
            'description' => $description,
            'properties' => $properties,
            ...($subject !== null ? ['subject_type' => $subject->getMorphClass(), 'subject_id' => $subject->getKey()] : []),
        ]);
    }
}
