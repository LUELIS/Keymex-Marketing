<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('standalone_bats', function (Blueprint $table) {
            $table->id();

            // Conseiller (depuis MongoDB)
            $table->string('advisor_mongo_id')->nullable();
            $table->string('advisor_name');
            $table->string('advisor_email')->nullable();
            $table->string('advisor_agency')->nullable();

            // Fichier BAT
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_mime')->nullable();

            // Description/Notes
            $table->string('title')->nullable();
            $table->text('description')->nullable();

            // Status: draft, sent, validated, refused, modifications_requested, converted
            $table->string('status')->default('draft');
            $table->text('client_comment')->nullable();

            // Token pour validation
            $table->uuid('validation_token')->unique();
            $table->timestamp('token_expires_at')->nullable();
            $table->timestamp('token_used_at')->nullable();

            // Dates
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('responded_at')->nullable();

            // Relation avec commande si converti
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();

            // Createur
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('standalone_bats');
    }
};
