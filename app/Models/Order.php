<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'advisor_mongo_id',
        'advisor_name',
        'advisor_email',
        'advisor_agency',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function batVersions(): HasMany
    {
        return $this->hasMany(BatVersion::class)->orderBy('version_number', 'desc');
    }

    public function latestBatVersion()
    {
        return $this->hasOne(BatVersion::class)->latestOfMany('version_number');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Alias pour createdBy (utilisé par OrderShow)
     */
    public function creator(): BelongsTo
    {
        return $this->createdBy();
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'En attente',
            'in_progress' => 'En cours',
            'bat_sent' => 'BAT envoyé',
            'validated' => 'Validé',
            'refused' => 'Refusé',
            'modifications_requested' => 'Modifications demandées',
            'completed' => 'Terminé',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'gray',
            'in_progress' => 'blue',
            'bat_sent' => 'yellow',
            'validated' => 'green',
            'refused' => 'red',
            'modifications_requested' => 'orange',
            'completed' => 'emerald',
            default => 'gray',
        };
    }
}
