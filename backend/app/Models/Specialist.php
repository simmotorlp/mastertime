<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Specialist extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'salon_id',
        'name',
        'translations',
        'position',
        'bio',
        'avatar',
        'working_hours',
        'active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'translations' => 'json',
        'working_hours' => 'json',
        'active' => 'boolean',
    ];

    /**
     * Get the salon that owns the specialist.
     */
    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }

    /**
     * Get the user that owns the specialist.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The services that belong to the specialist.
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'specialist_service');
    }

    /**
     * Get the appointments for the specialist.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the reviews for the specialist.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the specialist bio translation.
     *
     * @param string|null $locale
     * @return string|null
     */
    public function getTranslatedBio(?string $locale = null): ?string
    {
        return $this->translate('bio', $locale) ?? $this->bio;
    }

    /**
     * Set the specialist bio translation.
     *
     * @param string $value
     * @param string|null $locale
     * @return $this
     */
    public function setTranslatedBio(string $value, ?string $locale = null): self
    {
        return $this->setTranslation('bio', $value, $locale);
    }

    /**
     * Get the specialist position translation.
     *
     * @param string|null $locale
     * @return string|null
     */
    public function getTranslatedPosition(?string $locale = null): ?string
    {
        return $this->translate('position', $locale) ?? $this->position;
    }

    /**
     * Set the specialist position translation.
     *
     * @param string $value
     * @param string|null $locale
     * @return $this
     */
    public function setTranslatedPosition(string $value, ?string $locale = null): self
    {
        return $this->setTranslation('position', $value, $locale);
    }
}
