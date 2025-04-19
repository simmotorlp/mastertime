<?php

namespace Database\Factories;

use App\Models\Salon;
use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $duration = fake()->randomElement([30, 45, 60, 90, 120, 180]);
        $price = fake()->numberBetween(200, 2000);
        $discountedPrice = fake()->boolean(30) ? $price * 0.8 : null;

        return [
            'salon_id' => Salon::factory(),
            'category_id' => ServiceCategory::factory(),
            'name' => fake()->words(3, true),
            'translations' => json_encode([
                'description' => [
                    'en' => fake()->paragraph(),
                    'uk' => 'Опис послуги українською мовою',
                    'ru' => 'Описание услуги на русском языке',
                ],
            ]),
            'price' => $price,
            'discounted_price' => $discountedPrice,
            'duration' => $duration,
            'active' => true,
        ];
    }

    /**
     * Indicate that the service has a discount.
     */
    public function discounted($discountPercentage = 20): self
    {
        return $this->state(function (array $attributes) use ($discountPercentage) {
            $price = $attributes['price'] ?? 1000;
            $discountRate = (100 - $discountPercentage) / 100;

            return [
                'discounted_price' => round($price * $discountRate, 2),
            ];
        });
    }

    /**
     * Indicate that the service is inactive.
     */
    public function inactive(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'active' => false,
            ];
        });
    }

    /**
     * Set a fixed duration for the service.
     */
    public function duration(int $minutes): self
    {
        return $this->state(function (array $attributes) use ($minutes) {
            return [
                'duration' => $minutes,
            ];
        });
    }

    /**
     * Set a fixed price for the service.
     */
    public function price(float $price): self
    {
        return $this->state(function (array $attributes) use ($price) {
            return [
                'price' => $price,
            ];
        });
    }
}
