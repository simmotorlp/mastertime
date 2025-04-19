<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Salon;
use App\Models\Service;
use App\Models\Specialist;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Define positive review contents in Ukrainian and Russian
        $reviewContents = [
            'uk' => [
                'Чудовий сервіс! Дуже задоволена результатом.',
                'Майстер справжній професіонал своєї справи.',
                'Рекомендую цей салон всім своїм друзям.',
                'Мені сподобалось обслуговування та атмосфера салону.',
                'Дуже професійний підхід, дякую за роботу!',
                'Завжди приємно відвідувати цей салон.',
                'Висока якість послуг за розумною ціною.',
                'Чисто, комфортно, сучасно. Рекомендую!',
                'Робота виконана на відмінно, обов\'язково повернусь знову.',
            ],
            'ru' => [
                'Отличный сервис! Очень довольна результатом.',
                'Мастер настоящий профессионал своего дела.',
                'Рекомендую этот салон всем своим друзьям.',
                'Мне понравилось обслуживание и атмосфера салона.',
                'Очень профессиональный подход, спасибо за работу!',
                'Всегда приятно посещать этот салон.',
                'Высокое качество услуг по разумной цене.',
                'Чисто, комфортно, современно. Рекомендую!',
                'Работа выполнена на отлично, обязательно вернусь снова.',
            ],
        ];

        $salon = Salon::factory()->create();
        $language = fake()->randomElement(['uk', 'ru']);
        $content = fake()->randomElement($reviewContents[$language]);

        return [
            'user_id' => User::factory(),
            'salon_id' => $salon->id,
            'specialist_id' => null,
            'service_id' => null,
            'appointment_id' => null,
            'content' => $content,
            'rating' => fake()->numberBetween(3, 5),
            'approved' => true,
        ];
    }

    /**
     * Indicate that the review is for a specific service.
     */
    public function forService(Service $service = null): self
    {
        return $this->state(function (array $attributes) use ($service) {
            if (!$service) {
                $service = Service::factory()->create();
            }

            return [
                'service_id' => $service->id,
                'salon_id' => $service->salon_id,
            ];
        });
    }

    /**
     * Indicate that the review is for a specific specialist.
     */
    public function forSpecialist(Specialist $specialist = null): self
    {
        return $this->state(function (array $attributes) use ($specialist) {
            if (!$specialist) {
                $specialist = Specialist::factory()->create();
            }

            return [
                'specialist_id' => $specialist->id,
                'salon_id' => $specialist->salon_id,
            ];
        });
    }

    /**
     * Indicate that the review is for a specific appointment.
     */
    public function forAppointment(Appointment $appointment = null): self
    {
        return $this->state(function (array $attributes) use ($appointment) {
            if (!$appointment) {
                $appointment = Appointment::factory()->create();
            }

            return [
                'appointment_id' => $appointment->id,
                'salon_id' => $appointment->salon_id,
                'specialist_id' => $appointment->specialist_id,
                'service_id' => $appointment->service_id,
                'user_id' => $appointment->user_id,
            ];
        });
    }

    /**
     * Indicate that the review is not approved.
     */
    public function unapproved(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'approved' => false,
            ];
        });
    }

    /**
     * Set a specific rating for the review.
     */
    public function rating(int $rating): self
    {
        return $this->state(function (array $attributes) use ($rating) {
            return [
                'rating' => min(5, max(1, $rating)),
            ];
        });
    }

    /**
     * Create a negative review.
     */
    public function negative(): self
    {
        $negativeReviews = [
            'uk' => [
                'Не дуже задоволена результатом.',
                'Майстер запізнився і був непривітним.',
                'Сервіс не відповідає ціні.',
                'Не рекомендую цей салон.',
            ],
            'ru' => [
                'Не очень довольна результатом.',
                'Мастер опоздал и был неприветлив.',
                'Сервис не соответствует цене.',
                'Не рекомендую этот салон.',
            ],
        ];

        $language = fake()->randomElement(['uk', 'ru']);
        $content = fake()->randomElement($negativeReviews[$language]);

        return $this->state(function (array $attributes) use ($content) {
            return [
                'content' => $content,
                'rating' => fake()->numberBetween(1, 2),
            ];
        });
    }
}
