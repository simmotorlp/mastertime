<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceCategory extends Model
{
    use HasFactory, HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'translations',
        'slug',
        'order',
        'icon',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'translations' => 'json',
        'order' => 'integer',
    ];

    /**
     * Get the services for the category.
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'category_id');
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
