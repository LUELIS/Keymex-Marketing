<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'description',
        'primary_color',
        'secondary_color',
        'accent_color',
        'logo_path',
        'banner_path',
        'website',
        'email',
        'phone',
        'address',
        'office2_name',
        'office2_address',
        'facebook_url',
        'linkedin_url',
        'instagram_url',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * Get the signature templates for this brand
     */
    public function signatureTemplates(): HasMany
    {
        return $this->hasMany(SignatureTemplate::class);
    }

    /**
     * Get the full URL for the logo
     */
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? '/storage/' . $this->logo_path : null;
    }

    /**
     * Get the full URL for the banner
     */
    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner_path ? '/storage/' . $this->banner_path : null;
    }

    /**
     * Get default brand
     */
    public static function getDefault(): ?self
    {
        return static::where('is_default', true)->where('is_active', true)->first();
    }
}
