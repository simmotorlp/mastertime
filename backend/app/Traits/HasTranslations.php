<?php

namespace App\Traits;

use Illuminate\Support\Facades\App;

trait HasTranslations
{
    /**
     * Get a translated attribute.
     *
     * @param string $attribute
     * @param string|null $locale
     * @return mixed
     */
    public function translate(string $attribute, ?string $locale = null)
    {
        $locale = $locale ?: App::getLocale();
        $fallbackLocale = config('app.fallback_locale');

        // Check if the attribute exists directly on the model (non-translated)
        if (isset($this->attributes[$attribute])) {
            return $this->attributes[$attribute];
        }

        // Get translations
        $translations = $this->getTranslationsAttribute();

        // If no translations or attribute doesn't exist in translations, return null
        if (!$translations || !isset($translations[$attribute])) {
            return null;
        }

        // Try to get the translation for the requested locale
        if (isset($translations[$attribute][$locale])) {
            return $translations[$attribute][$locale];
        }

        // Fallback to the default locale
        if (isset($translations[$attribute][$fallbackLocale])) {
            return $translations[$attribute][$fallbackLocale];
        }

        // If still no translation, return the first available
        if (!empty($translations[$attribute])) {
            return reset($translations[$attribute]);
        }

        return null;
    }

    /**
     * Set a translated attribute.
     *
     * @param string $attribute
     * @param mixed $value
     * @param string|null $locale
     * @return $this
     */
    public function setTranslation(string $attribute, $value, ?string $locale = null)
    {
        $locale = $locale ?: App::getLocale();

        $translations = $this->getTranslationsAttribute() ?: [];

        if (!isset($translations[$attribute])) {
            $translations[$attribute] = [];
        }

        $translations[$attribute][$locale] = $value;

        $this->attributes['translations'] = json_encode($translations);

        return $this;
    }

    /**
     * Set translations for multiple attributes.
     *
     * @param array $translations
     * @param string|null $locale
     * @return $this
     */
    public function setTranslations(array $translations, ?string $locale = null)
    {
        foreach ($translations as $attribute => $value) {
            $this->setTranslation($attribute, $value, $locale);
        }

        return $this;
    }

    /**
     * Get all translations for an attribute.
     *
     * @param string $attribute
     * @return array|null
     */
    public function getTranslations(string $attribute)
    {
        $translations = $this->getTranslationsAttribute();

        return $translations[$attribute] ?? null;
    }

    /**
     * Get the translations attribute.
     *
     * @return array
     */
    public function getTranslationsAttribute()
    {
        return isset($this->attributes['translations'])
            ? json_decode($this->attributes['translations'], true)
            : [];
    }

    /**
     * Determine if a translation exists.
     *
     * @param string $attribute
     * @param string|null $locale
     * @return bool
     */
    public function hasTranslation(string $attribute, ?string $locale = null)
    {
        $locale = $locale ?: App::getLocale();
        $translations = $this->getTranslationsAttribute();

        return isset($translations[$attribute][$locale]);
    }
}
