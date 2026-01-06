<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();

            // Couleurs
            $table->string('primary_color')->default('#8B5CF6'); // Violet KEYMEX
            $table->string('secondary_color')->default('#6c757d');
            $table->string('accent_color')->nullable();

            // Logo et images
            $table->string('logo_path')->nullable();
            $table->string('banner_path')->nullable();

            // Informations de contact
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('office2_name')->nullable();
            $table->text('office2_address')->nullable();

            // Reseaux sociaux
            $table->string('facebook_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('instagram_url')->nullable();

            // Parametres
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
