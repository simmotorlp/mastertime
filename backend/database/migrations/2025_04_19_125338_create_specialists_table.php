<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('specialists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('salon_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->jsonb('translations')->nullable();
            $table->string('position')->nullable();
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->jsonb('working_hours')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('specialist_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specialist_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->unique(['specialist_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specialist_service');
        Schema::dropIfExists('specialists');
    }
};
