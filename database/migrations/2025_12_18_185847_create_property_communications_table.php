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
        Schema::create('property_communications', function (Blueprint $table) {
            $table->id();
            $table->string('property_mongo_id')->index();
            $table->enum('action_type', [
                'facebook_post',
                'instagram_story',
                'linkedin_post',
                'newsletter',
                'flyer',
                'other'
            ]);
            $table->date('action_date');
            $table->string('link')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_communications');
    }
};
