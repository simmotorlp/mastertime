<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'salon_id',
        'category_id',
        'name',
        'translations',
        'price',
        'discounted_price',
        'duration',
        'active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'translations' => 'json',
        'price' => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'duration' => 'integer',
        'active' => 'boolean',
    ];

    /**
     * Get the salon that owns the service.
     */
    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }

    /**
     * Get the category that owns the service.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    /**
     * The specialists that belong to the service.
     */
    public function specialists(): BelongsToMany
    {
        return $this->belongsToMany(Specialist::class, 'specialist_service');
    }

    /**
     * Get the appointments for the service.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the reviews for the service.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the media for the service.
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
