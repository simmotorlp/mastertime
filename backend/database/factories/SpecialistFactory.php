<?php

namespace Database\Factories;

use App\Models\Salon;
use App\Models\Specialist;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specialist>
 */
class SpecialistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $positions = [
            'Hair Stylist' => ['uk' => 'Стиліст з волосся', 'ru' => 'Стилист по волосам'],
            'Nail Technician' => ['uk' => 'Майстер манікюру', 'ru' => 'Мастер маникюра'],
            'Makeup Artist' => ['uk' => 'Візажист', 'ru' => 'Визажист'],
            'Barber' => ['uk' => 'Барбер', 'ru' => 'Барбер'],
            'Cosmetologist' => ['uk' => 'Косметолог', 'ru' => 'Косметолог'],
            'Lash Artist' => ['uk' => 'Майстер з нарощування вій', 'ru' => 'Мастер по наращиванию ресниц'],
            'Massage Therapist' => ['uk' => 'Масажист', 'ru' => 'Массажист'],
        ];

        $position = fake()->randomElement(array_keys($positions));
        $translatedPositions = $positions[$position];

        return [
            'user_id' => null,
            'salon_id' => Salon::factory(),
            'name' => fake()->firstName() . ' ' . fake()->lastName(),
            'position' => $position,
            'translations' => json_encode([
                'bio' => [
                    'en' => fake()->paragraph(),
                    'uk' => 'Професійний майстер з багаторічним досвідом роботи.',
                    'ru' => 'Профессиональный мастер с многолетним опытом работы.',
                ],
                'position' => [
                    'en' => $position,
                    'uk' => $translatedPositions['uk'],
                    'ru' => $translatedPositions['ru'],
                ],
            ]),
            'bio' => fake()->paragraph(),
            'avatar' => 'specialists/' . fake()->word() . '.jpg',
            'working_hours' => json_encode([
                'monday' => ['09:00', '18:00'],
                'tuesday' => ['09:00', '18:00'],
                'wednesday' => ['09:00', '18:00'],
                'thursday' => ['09:00', '18:00'],
                'friday' => ['09:00', '18:00'],
                'saturday' => ['10:00', '16:00'],
                'sunday' => ['closed'],
            ]),
            'active' => true,
        ];
    }

    /**
     * Indicate that the specialist has a user account.
     */
    public function withUser(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'user_id' => User::factory(),
            ];
        });
    }

    /**
     * Indicate that the specialist is inactive.
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
     * Define a specific position for the specialist.
     */
    public function position(string $position): self
    {
        $positions = [
            'Hair Stylist' => ['uk' => 'Стиліст з волосся', 'ru' => 'Стилист по волосам'],
            'Nail Technician' => ['uk' => 'Майстер манікюру', 'ru' => 'Мастер маникюра'],
            'Makeup Artist' => ['uk' => 'Візажист', 'ru' => 'Визажист'],
            'Barber' => ['uk' => 'Барбер', 'ru' => 'Барбер'],
            'Cosmetologist' => ['uk' => 'Косметолог', 'ru' => 'Косметолог'],
            'Lash Artist' => ['uk' => 'Майстер з нарощування вій', 'ru' => 'Мастер по наращиванию ресниц'],
            'Massage Therapist' => ['uk' => 'Масажист', 'ru' => 'Массажист'],
        ];

        $translatedPositions = $positions[$position] ?? ['uk' => $position, 'ru' => $position];

        return $this->state(function (array $attributes) use ($position, $translatedPositions) {
            $translations = json_decode($attributes['translations'], true) ?? [];
            $translations['position'] = [
                'en' => $position,
                'uk' => $translatedPositions['uk'],
                'ru' => $translatedPositions['ru'],
            ];

            return [
                'position' => $position,
                'translations' => json_encode($translations),
            ];
        });
    }
}
