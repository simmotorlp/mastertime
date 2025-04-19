<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceCategory>
 */
class ServiceCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(2, true);
        $slug = Str::slug($name);

        $icons = [
            'fa-scissors',
            'fa-hand-sparkles',
            'fa-eye',
            'fa-hands',
            'fa-face-smile',
            'fa-child',
            'fa-eye-lash',
            'fa-spa',
            'fa-heart',
        ];

        return [
            'name' => ucwords($name),
            'translations' => json_encode([
                'description' => [
                    'en' => fake()->paragraph(),
                    'uk' => 'Опис категорії українською мовою',
                    'ru' => 'Описание категории на русском языке',
                ],
                'name' => [
                    'en' => ucwords($name),
                    'uk' => 'Назва українською',
                    'ru' => 'Название на русском',
                ],
            ]),
            'slug' => $slug,
            'order' => fake()->numberBetween(1, 10),
            'icon' => fake()->randomElement($icons),
        ];
    }

    /**
     * Set a specific icon for the category.
     */
    public function icon(string $icon): self
    {
        return $this->state(function (array $attributes) use ($icon) {
            return [
                'icon' => $icon,
            ];
        });
    }

    /**
     * Set the display order for the category.
     */
    public function order(int $order): self
    {
        return $this->state(function (array $attributes) use ($order) {
            return [
                'order' => $order,
            ];
        });
    }
}
