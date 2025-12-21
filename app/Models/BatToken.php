<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatToken extends Model
{
    protected $fillable = [
        'bat_version_id',
        'token',
        'expires_at',
        'used_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function batVersion(): BelongsTo
    {
        return $this->belongsTo(BatVersion::class);
    }

    public function isValid(): bool
    {
        return $this->expires_at->isFuture() && is_null($this->used_at);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function markAsUsed(?string $ipAddress = null, ?string $userAgent = null): void
    {
        $this->update([
            'used_at' => now(),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }
}
