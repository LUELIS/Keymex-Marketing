<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class StorageSetting extends Model
{
    protected $fillable = [
        'driver',
        's3_key',
        's3_secret',
        's3_region',
        's3_bucket',
        's3_endpoint',
        's3_url',
        's3_path_style',
        'is_active',
    ];

    protected $casts = [
        's3_path_style' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        's3_secret',
    ];

    /**
     * Recupere les parametres de stockage actifs (singleton pattern)
     */
    public static function getSettings(): ?self
    {
        return Cache::remember('storage_settings', 3600, function () {
            return self::where('is_active', true)->first();
        });
    }

    /**
     * Vide le cache des parametres de stockage
     */
    public static function clearCache(): void
    {
        Cache::forget('storage_settings');
    }

    /**
     * Recupere ou cree les parametres (singleton)
     */
    public static function getInstance(): self
    {
        return self::first() ?? new self();
    }

    /**
     * Retourne le disk a utiliser (s3 si actif, sinon public)
     */
    public static function getDisk(): string
    {
        $settings = self::getSettings();

        if ($settings && $settings->is_active && $settings->driver === 's3') {
            return 's3';
        }

        return 'public';
    }

    /**
     * Verifie si S3 est actif
     */
    public static function isS3Active(): bool
    {
        $settings = self::getSettings();

        return $settings && $settings->is_active && $settings->driver === 's3';
    }

    /**
     * Boot method pour vider le cache apres modification
     */
    protected static function booted(): void
    {
        static::saved(function () {
            self::clearCache();
        });

        static::deleted(function () {
            self::clearCache();
        });
    }
}
