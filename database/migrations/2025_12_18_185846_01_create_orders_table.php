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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('advisor_mongo_id')->index();
            $table->string('advisor_name');
            $table->string('advisor_email')->nullable();
            $table->string('advisor_agency')->nullable();
            $table->enum('status', [
                'pending',
                'in_progress',
                'bat_sent',
                'validated',
                'refused',
                'modifications_requested',
                'completed'
            ])->default('pending');
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
        Schema::dropIfExists('orders');
    }
};
