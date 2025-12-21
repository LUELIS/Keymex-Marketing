<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class BatVersion extends Model
{
    protected $fillable = [
        'order_id',
        'version_number',
        'file_path',
        'file_name',
        'file_mime',
        'status',
        'comment',
        'sent_at',
        'responded_at',
        'sent_by',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function tokens(): HasMany
    {
        return $this->hasMany(BatToken::class);
    }

    public function activeToken(): HasOne
    {
        return $this->hasOne(BatToken::class)
            ->where('expires_at', '>', now())
            ->whereNull('used_at')
            ->latest();
    }

    public function sentBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function createToken(int $expirationDays = 30): BatToken
    {
        return $this->tokens()->create([
            'token' => Str::uuid(),
            'expires_at' => now()->addDays($expirationDays),
        ]);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'En attente',
            'validated' => 'Validé',
            'refused' => 'Refusé',
            'modifications_requested' => 'Modifications demandées',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'validated' => 'green',
            'refused' => 'red',
            'modifications_requested' => 'orange',
            default => 'gray',
        };
    }
}
