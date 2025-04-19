<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('salon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('specialist_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete();
            $table->text('content');
            $table->integer('rating')->default(5);
            $table->boolean('approved')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Add indexes for common queries
            $table->index(['salon_id', 'approved']);
            $table->index(['specialist_id', 'approved']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
