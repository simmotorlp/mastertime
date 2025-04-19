<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salon extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'owner_id',
        'slug',
        'name',
        'translations',
        'address',
        'city',
        'phone',
        'email',
        'website',
        'social_links',
        'working_hours',
        'location',
        'active',
        'verified',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'translations' => 'json',
        'social_links' => 'json',
        'working_hours' => 'json',
        'active' => 'boolean',
        'verified' => 'boolean',
    ];

    /**
     * Get the owner that owns the salon.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the services for the salon.
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Get the specialists for the salon.
     */
    public function specialists(): HasMany
    {
        return $this->hasMany(Specialist::class);
    }

    /**
     * Get the appointments for the salon.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the reviews for the salon.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the media for the salon.
     */
    public function media(): HasMany
    {
        return $this->hasMany(Media::class, 'model_id')
            ->where('model_type', self::class);
    }

    /**
     * Get the description translation.
     *
     * @param string|null $locale
     * @return string|null
     */
    public function getDescription(?string $locale = null): ?string
    {
        return $this->translate('description', $locale);
    }

    /**
     * Set the description translation.
     *
     * @param string $value
     * @param string|null $locale
     * @return $this
     */
    public function setDescription(string $value, ?string $locale = null): self
    {
        return $this->setTranslation('description', $value, $locale);
    }
}
