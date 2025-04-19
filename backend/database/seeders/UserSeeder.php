<?php

namespace Database\Seeders;

use App\Models\NotificationPreference;
use App\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Serhii Symonov',
            'email' => 'simmotorlp@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        // Create admin user
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::updateOrCreate(
            ['email' => 'admin@mastertime.ua'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
                'phone' => '+380501234567',
                'language' => 'uk',
                'profile' => json_encode([
                    'bio' => 'Administrator account for the MasterTime platform',
                    'position' => 'System Administrator',
                ]),
                'active' => true,
                'last_login_at' => now(),
            ]
        );

        // Create notification preferences for admin
        NotificationPreference::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'email_appointment_reminder' => true,
                'email_appointment_confirmation' => true,
                'email_appointment_cancellation' => true,
                'email_marketing' => false,
                'sms_appointment_reminder' => true,
                'sms_appointment_confirmation' => true,
                'sms_appointment_cancellation' => true,
                'sms_marketing' => false,
            ]
        );

        // Create salon owner user
        $ownerRole = Role::where('name', 'salon_owner')->first();
        $owner = User::updateOrCreate(
            ['email' => 'owner@mastertime.ua'],
            [
                'name' => 'Salon Owner',
                'password' => Hash::make('password'),
                'role_id' => $ownerRole->id,
                'email_verified_at' => now(),
                'phone' => '+380502345678',
                'language' => 'uk',
                'profile' => json_encode([
                    'bio' => 'Owner of beauty salons',
                    'position' => 'Business Owner',
                ]),
                'active' => true,
                'last_login_at' => now(),
            ]
        );

        // Create notification preferences for owner
        NotificationPreference::updateOrCreate(
            ['user_id' => $owner->id],
            [
                'email_appointment_reminder' => true,
                'email_appointment_confirmation' => true,
                'email_appointment_cancellation' => true,
                'email_marketing' => true,
                'sms_appointment_reminder' => true,
                'sms_appointment_confirmation' => true,
                'sms_appointment_cancellation' => true,
                'sms_marketing' => true,
            ]
        );

        // Create specialist user
        $specialistRole = Role::where('name', 'specialist')->first();
        $specialist = User::updateOrCreate(
            ['email' => 'specialist@mastertime.ua'],
            [
                'name' => 'Beauty Specialist',
                'password' => Hash::make('password'),
                'role_id' => $specialistRole->id,
                'email_verified_at' => now(),
                'phone' => '+380503456789',
                'language' => 'uk',
                'profile' => json_encode([
                    'bio' => 'Professional beauty specialist with 5 years of experience',
                    'position' => 'Master Stylist',
                ]),
                'active' => true,
                'last_login_at' => now(),
            ]
        );

        // Create notification preferences for specialist
        NotificationPreference::updateOrCreate(
            ['user_id' => $specialist->id],
            [
                'email_appointment_reminder' => true,
                'email_appointment_confirmation' => true,
                'email_appointment_cancellation' => true,
                'email_marketing' => false,
                'sms_appointment_reminder' => true,
                'sms_appointment_confirmation' => true,
                'sms_appointment_cancellation' => true,
                'sms_marketing' => false,
            ]
        );

        // Create 10 client users
        $clientRole = Role::where('name', 'client')->first();
        for ($i = 1; $i <= 10; $i++) {
            $client = User::updateOrCreate(
                ['email' => "client{$i}@example.com"],
                [
                    'name' => "Client User {$i}",
                    'password' => Hash::make('password'),
                    'role_id' => $clientRole->id,
                    'email_verified_at' => now(),
                    'phone' => "+38050{$i}56789",
                    'language' => $i % 2 == 0 ? 'uk' : 'ru',
                    'profile' => json_encode([
                        'bio' => "Client user {$i} for testing purposes",
                    ]),
                    'active' => true,
                    'last_login_at' => now()->subDays(rand(1, 30)),
                ]
            );

            // Create notification preferences for clients
            NotificationPreference::updateOrCreate(
                ['user_id' => $client->id],
                [
                    'email_appointment_reminder' => $i % 2 == 0,
                    'email_appointment_confirmation' => true,
                    'email_appointment_cancellation' => true,
                    'email_marketing' => $i % 3 == 0,
                    'sms_appointment_reminder' => $i % 2 != 0,
                    'sms_appointment_confirmation' => true,
                    'sms_appointment_cancellation' => true,
                    'sms_marketing' => $i % 4 == 0,
                ]
            );
        }
    }
}
