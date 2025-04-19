<?php

namespace Database\Factories;

use App\Models\Salon;
use App\Models\Service;
use App\Models\Specialist;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $salon = Salon::factory()->create();
        $specialist = Specialist::factory()->create(['salon_id' => $salon->id]);
        $service = Service::factory()->create(['salon_id' => $salon->id]);
        $user = User::factory()->create();

        $startTime = Carbon::now()->addDays(rand(1, 14))->setHour(rand(9, 17))->setMinute(0)->setSecond(0);
        $endTime = $startTime->copy()->addMinutes($service->duration ?? 60);

        return [
            'user_id' => $user->id,
            'salon_id' => $salon->id,
            'specialist_id' => $specialist->id,
            'service_id' => $service->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'price' => $service->price ?? 500,
            'status' => 'pending',
            'notes' => null,
            'client_name' => $user->name,
            'client_phone' => $user->phone,
            'client_email' => $user->email,
        ];
    }

    /**
     * Indicate that the appointment is confirmed.
     */
    public function confirmed(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'confirmed',
            ];
        });
    }

    /**
     * Indicate that the appointment is completed.
     */
    public function completed(): self
    {
        return $this->state(function (array $attributes) {
            // Set the appointment in the past for completed status
            $startTime = Carbon::now()->subDays(rand(1, 30))->setHour(rand(9, 17))->setMinute(0)->setSecond(0);
            $endTime = $startTime->copy()->addMinutes(60);

            return [
                'status' => 'completed',
                'start_time' => $startTime,
                'end_time' => $endTime,
            ];
        });
    }

    /**
     * Indicate that the appointment is cancelled.
     */
    public function cancelled(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'cancelled',
                'notes' => 'Cancelled by client: ' . fake()->sentence(),
            ];
        });
    }

    /**
     * Schedule the appointment at a specific time.
     */
    public function scheduled(Carbon $startTime, int $duration = null): self
    {
        return $this->state(function (array $attributes) use ($startTime, $duration) {
            $endTime = $startTime->copy()->addMinutes($duration ?? 60);

            return [
                'start_time' => $startTime,
                'end_time' => $endTime,
            ];
        });
    }

    /**
     * Set a specific service for the appointment.
     */
    public function forService(Service $service = null): self
    {
        return $this->state(function (array $attributes) use ($service) {
            if (!$service) {
                return [];
            }

            $startTime = Carbon::parse($attributes['start_time']);
            $endTime = $startTime->copy()->addMinutes($service->duration);

            return [
                'service_id' => $service->id,
                'salon_id' => $service->salon_id,
                'price' => $service->discounted_price ?? $service->price,
                'end_time' => $endTime,
            ];
        });
    }

    /**
     * Set a specific specialist for the appointment.
     */
    public function withSpecialist(Specialist $specialist = null): self
    {
        return $this->state(function (array $attributes) use ($specialist) {
            if (!$specialist) {
                return [];
            }

            return [
                'specialist_id' => $specialist->id,
                'salon_id' => $specialist->salon_id,
            ];
        });
    }

    /**
     * Create an appointment for a guest client (no user account).
     */
    public function forGuest(string $name = null, string $phone = null, string $email = null): self
    {
        return $this->state(function (array $attributes) use ($name, $phone, $email) {
            return [
                'user_id' => null,
                'client_name' => $name ?? fake()->name(),
                'client_phone' => $phone ?? '+380' . fake()->numberBetween(50, 99) . fake()->numerify('#######'),
                'client_email' => $email ?? fake()->safeEmail(),
            ];
        });
    }
}
