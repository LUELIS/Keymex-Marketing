<?php

namespace App\Providers;

use App\Models\StorageSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class DynamicStorageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Charge la config S3 depuis la BDD (web et CLI)
        $this->configureStorageFromDatabase();
    }

    /**
     * Configure le storage S3 depuis la base de donnees
     */
    protected function configureStorageFromDatabase(): void
    {
        try {
            // Verifie que la table existe
            if (!Schema::hasTable('storage_settings')) {
                return;
            }

            $settings = StorageSetting::getSettings();

            if ($settings && $settings->is_active && $settings->driver === 's3') {
                config([
                    'filesystems.disks.s3.driver' => 's3',
                    'filesystems.disks.s3.key' => $settings->s3_key,
                    'filesystems.disks.s3.secret' => $settings->s3_secret,
                    'filesystems.disks.s3.region' => $settings->s3_region,
                    'filesystems.disks.s3.bucket' => $settings->s3_bucket,
                    'filesystems.disks.s3.endpoint' => $settings->s3_endpoint,
                    'filesystems.disks.s3.url' => $settings->s3_url,
                    'filesystems.disks.s3.use_path_style_endpoint' => $settings->s3_path_style,
                ]);
            }
        } catch (\Exception $e) {
            // En cas d'erreur (table inexistante, etc.), on continue avec la config par defaut
            report($e);
        }
    }
}
