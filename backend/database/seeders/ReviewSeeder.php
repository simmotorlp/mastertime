<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Review;
use App\Models\Salon;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get completed appointments
        $completedAppointments = Appointment::where('status', 'completed')->get();

        if ($completedAppointments->isEmpty()) {
            $this->command->info('No completed appointments found. Please run AppointmentSeeder first.');
            return;
        }

        // Get clients
        $clients = User::whereHas('role', function ($query) {
            $query->where('name', 'client');
        })->get();

        if ($clients->isEmpty()) {
            $this->command->info('No client users found. Please run UserSeeder first.');
            return;
        }

        // Sample reviews content in both Ukrainian and Russian
        $reviewContents = [
            'uk' => [
                'Чудовий сервіс! Дуже задоволена результатом.',
                'Майстер справжній професіонал своєї справи.',
                'Рекомендую цей салон всім своїм друзям.',
                'Непогано, але є над чим працювати.',
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
                'Неплохо, но есть над чем работать.',
                'Мне понравилось обслуживание и атмосфера салона.',
                'Очень профессиональный подход, спасибо за работу!',
                'Всегда приятно посещать этот салон.',
                'Высокое качество услуг по разумной цене.',
                'Чисто, комфортно, современно. Рекомендую!',
                'Работа выполнена на отлично, обязательно вернусь снова.',
            ],
        ];

        // Create reviews for approximately 70% of completed appointments
        foreach ($completedAppointments as $appointment) {
            // 70% chance to create a review
            if (rand(1, 10) <= 7) {
                $language = $appointment->user->language ?? 'uk';
                $content = $reviewContents[$language][array_rand($reviewContents[$language])];

                // Random rating between 3 and 5 (more positive reviews than negative)
                // Ensure it's an integer
                $rating = rand(3, 5);

                // 80% of reviews are approved
                $approved = rand(1, 10) <= 8;

                $reviewData = [
                    'user_id' => $appointment->user_id,
                    'salon_id' => $appointment->salon_id,
                    'specialist_id' => $appointment->specialist_id,
                    'service_id' => $appointment->service_id,
                    'appointment_id' => $appointment->id,
                    'content' => $content,
                    'rating' => $rating,
                    'approved' => $approved,
                ];

                Review::create($reviewData);
            }
        }

        // Create some additional salon reviews without appointment reference
        $salons = Salon::all();

        foreach ($salons as $salon) {
            // Create 5 additional reviews per salon
            for ($i = 0; $i < 5; $i++) {
                $client = $clients->random();
                $language = $client->language ?? 'uk';
                $content = $reviewContents[$language][array_rand($reviewContents[$language])];

                // Fixed ratings as integer values only
                $rating = rand(1, 5);

                // 80% of reviews are approved
                $approved = rand(1, 10) <= 8;

                $reviewData = [
                    'user_id' => $client->id,
                    'salon_id' => $salon->id,
                    'content' => $content,
                    'rating' => $rating,
                    'approved' => $approved,
                ];

                Review::create($reviewData);
            }
        }
    }
}
