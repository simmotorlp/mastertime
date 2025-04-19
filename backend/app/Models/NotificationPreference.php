<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationPreference extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'email_appointment_reminder',
        'email_appointment_confirmation',
        'email_appointment_cancellation',
        'email_marketing',
        'sms_appointment_reminder',
        'sms_appointment_confirmation',
        'sms_appointment_cancellation',
        'sms_marketing',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_appointment_reminder' => 'boolean',
        'email_appointment_confirmation' => 'boolean',
        'email_appointment_cancellation' => 'boolean',
        'email_marketing' => 'boolean',
        'sms_appointment_reminder' => 'boolean',
        'sms_appointment_confirmation' => 'boolean',
        'sms_appointment_cancellation' => 'boolean',
        'sms_marketing' => 'boolean',
    ];

    /**
     * Get the user that owns the notification preference.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
