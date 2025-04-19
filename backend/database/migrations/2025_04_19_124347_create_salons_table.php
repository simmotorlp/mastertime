<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('name');
            $table->jsonb('translations')->nullable();
            $table->text('address');
            $table->string('city');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->jsonb('social_links')->nullable();
            $table->jsonb('working_hours')->nullable();
            $table->point('location')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('verified')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Add spatial index for faster geo queries
            $table->spatialIndex('location');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salons');
    }
};
