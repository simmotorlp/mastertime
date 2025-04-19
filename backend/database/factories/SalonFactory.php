<?php

namespace Database\Factories;

use App\Models\Salon;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Salon>
 */
class SalonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company() . ' Beauty';
        $slug = Str::slug($name);

        // Random coordinates in Ukraine
        $lat = fake()->latitude(44.3, 52.3);
        $lng = fake()->longitude(22.1, 40.2);

        // Convert to PostgreSQL point
        $location = DB::raw("ST_MakePoint($lng, $lat)");

        return [
            'owner_id' => User::factory(),
            'slug' => $slug,
            'name' => $name,
            'translations' => json_encode([
                'description' => [
                    'en' => fake()->paragraph(),
                    'uk' => 'Опис салону краси українською мовою',
                    'ru' => 'Описание салона красоты на русском языке',
                ],
            ]),
            'address' => fake()->streetAddress(),
            'city' => fake()->randomElement(['Київ', 'Львів', 'Одеса', 'Харків', 'Дніпро']),
            'phone' => '+380' . fake()->numberBetween(50, 99) . fake()->numerify('#######'),
            'email' => fake()->companyEmail(),
            'website' => 'https://' . $slug . '.ua',
            'social_links' => json_encode([
                'instagram' => $slug,
                'facebook' => $slug,
            ]),
            'working_hours' => json_encode([
                'monday' => ['09:00', '18:00'],
                'tuesday' => ['09:00', '18:00'],
                'wednesday' => ['09:00', '18:00'],
                'thursday' => ['09:00', '18:00'],
                'friday' => ['09:00', '18:00'],
                'saturday' => ['10:00', '16:00'],
                'sunday' => ['closed'],
            ]),
            'location' => $location,
            'active' => true,
            'verified' => fake()->boolean(80),
        ];
    }

    /**
     * Indicate that the salon is verified.
     */
    public function verified(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'verified' => true,
            ];
        });
    }

    /**
     * Indicate that the salon is not verified.
     */
    public function unverified(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'verified' => false,
            ];
        });
    }

    /**
     * Indicate that the salon is inactive.
     */
    public function inactive(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'active' => false,
            ];
        });
    }
}
