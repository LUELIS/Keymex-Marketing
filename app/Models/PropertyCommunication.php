<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyCommunication extends Model
{
    protected $fillable = [
        'property_mongo_id',
        'action_type',
        'action_date',
        'link',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'action_date' => 'date',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getActionTypeLabelAttribute(): string
    {
        return match ($this->action_type) {
            'facebook_post' => 'Post Facebook',
            'instagram_story' => 'Story Instagram',
            'linkedin_post' => 'Publication LinkedIn',
            'newsletter' => 'Newsletter',
            'flyer' => 'Flyer distribué',
            'other' => 'Autre',
            default => $this->action_type,
        };
    }

    public function getActionTypeIconAttribute(): string
    {
        return match ($this->action_type) {
            'facebook_post' => 'facebook',
            'instagram_story' => 'instagram',
            'linkedin_post' => 'linkedin',
            'newsletter' => 'envelope',
            'flyer' => 'document',
            'other' => 'dots-horizontal',
            default => 'question-mark-circle',
        };
    }
}
