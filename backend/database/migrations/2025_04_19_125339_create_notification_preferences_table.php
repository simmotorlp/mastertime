<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('email_appointment_reminder')->default(true);
            $table->boolean('email_appointment_confirmation')->default(true);
            $table->boolean('email_appointment_cancellation')->default(true);
            $table->boolean('email_marketing')->default(false);
            $table->boolean('sms_appointment_reminder')->default(true);
            $table->boolean('sms_appointment_confirmation')->default(true);
            $table->boolean('sms_appointment_cancellation')->default(true);
            $table->boolean('sms_marketing')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
