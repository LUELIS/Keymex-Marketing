<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Format extends Model
{
    protected $fillable = [
        'support_type_id',
        'name',
        'slug',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function supportType(): BelongsTo
    {
        return $this->belongsTo(SupportType::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
