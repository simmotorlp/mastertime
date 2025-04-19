<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
    use HasFactory, HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'model_type',
        'model_id',
        'collection_name',
        'name',
        'file_name',
        'mime_type',
        'disk',
        'size',
        'custom_properties',
        'translations',
        'order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'custom_properties' => 'json',
        'translations' => 'json',
        'size' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Get the owning model.
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the alt text translation.
     *
     * @param string|null $locale
     * @return string|null
     */
    public function getAltText(?string $locale = null): ?string
    {
        return $this->translate('alt_text', $locale);
    }

    /**
     * Set the alt text translation.
     *
     * @param string $value
     * @param string|null $locale
     * @return $this
     */
    public function setAltText(string $value, ?string $locale = null): self
    {
        return $this->setTranslation('alt_text', $value, $locale);
    }

    /**
     * Get the caption translation.
     *
     * @param string|null $locale
     * @return string|null
     */
    public function getCaption(?string $locale = null): ?string
    {
        return $this->translate('caption', $locale);
    }

    /**
     * Set the caption translation.
     *
     * @param string $value
     * @param string|null $locale
     * @return $this
     */
    public function setCaption(string $value, ?string $locale = null): self
    {
        return $this->setTranslation('caption', $value, $locale);
    }
}
