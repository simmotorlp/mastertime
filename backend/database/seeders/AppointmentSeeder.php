<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Salon;
use App\Models\Service;
use App\Models\Specialist;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get clients
        $clients = User::whereHas('role', function ($query) {
            $query->where('name', 'client');
        })->get();

        if ($clients->isEmpty()) {
            $this->command->info('No client users found. Please run UserSeeder first.');
            return;
        }

        // Get salons with their services and specialists
        $salons = Salon::with(['services', 'specialists'])->get();

        if ($salons->isEmpty()) {
            $this->command->info('No salons found. Please run SalonSeeder first.');
            return;
        }

        // Create appointments for different statuses
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];

        // Define number of appointments to create per status
        $appointmentsPerStatus = 5;

        // Create appointments starting from yesterday to 30 days in the future
        $yesterday = Carbon::yesterday();

        foreach ($salons as $salon) {
            if ($salon->services->isEmpty() || $salon->specialists->isEmpty()) {
                $this->command->info("Salon '{$salon->name}' has no services or specialists. Skipping.");
                continue;
            }

            foreach ($statuses as $status) {
                for ($i = 0; $i < $appointmentsPerStatus; $i++) {
                    // Randomly select a client
                    $client = $clients->random();

                    // Randomly select a service for this salon
                    $service = $salon->services->random();

                    // Find specialists who offer this service, or fallback to any specialist
                    $specialist = $service->specialists->filter(function ($specialist) use ($salon) {
                        return $specialist->salon_id === $salon->id;
                    })->first();

                    if (!$specialist) {
                        $specialist = $salon->specialists->random();
                    }

                    // Calculate start time
                    $daysToAdd = rand(-1, 30);
                    $hours = [9, 10, 11, 12, 14, 15, 16, 17, 18];
                    $startTime = $yesterday->copy()->addDays($daysToAdd)->setHour($hours[array_rand($hours)])->setMinute(0)->setSecond(0);

                    // For completed or cancelled appointments, set them in the past
                    if ($status === 'completed' || $status === 'cancelled') {
                        $startTime = $yesterday->copy()->subDays(rand(1, 30))->setHour($hours[array_rand($hours)])->setMinute(0)->setSecond(0);
                    }

                    // Calculate end time based on service duration
                    $endTime = $startTime->copy()->addMinutes($service->duration);

                    // Create appointment data
                    $appointmentData = [
                        'user_id' => $client->id,
                        'salon_id' => $salon->id,
                        'specialist_id' => $specialist->id,
                        'service_id' => $service->id,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'price' => $service->price,
                        'status' => $status,
                        'notes' => $status === 'cancelled' ? 'Appointment cancelled by client.' : null,
                        'client_name' => $client->name,
                        'client_phone' => $client->phone,
                        'client_email' => $client->email,
                    ];

                    // Create appointment
                    Appointment::create($appointmentData);
                }
            }
        }
    }
}
