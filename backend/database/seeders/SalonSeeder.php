<?php

namespace Database\Seeders;

use App\Models\Salon;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SalonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get salon owner user
        $owner = User::where('email', 'owner@mastertime.ua')->first();

        if (!$owner) {
            $ownerRole = Role::where('name', 'salon_owner')->first();
            $owner = User::factory()->create([
                'name' => 'Salon Owner',
                'email' => 'owner@mastertime.ua',
                'role_id' => $ownerRole->id,
            ]);
        }

        $salons = [
            [
                'name' => 'Beauty Haven',
                'slug' => 'beauty-haven',
                'address' => 'вул. Хрещатик, 12',
                'city' => 'Київ',
                'phone' => '+380441234567',
                'email' => 'info@beautyhaven.ua',
                'website' => 'https://beautyhaven.ua',
                'social_links' => [
                    'instagram' => 'beauty_haven_ua',
                    'facebook' => 'beautyhaven.ua',
                ],
                'working_hours' => [
                    'monday' => ['09:00', '20:00'],
                    'tuesday' => ['09:00', '20:00'],
                    'wednesday' => ['09:00', '20:00'],
                    'thursday' => ['09:00', '20:00'],
                    'friday' => ['09:00', '20:00'],
                    'saturday' => ['10:00', '18:00'],
                    'sunday' => ['closed'],
                ],
                'translations' => [
                    'description' => [
                        'en' => 'A premium beauty salon offering a wide range of services',
                        'uk' => 'Преміум салон краси, який пропонує широкий спектр послуг',
                        'ru' => 'Премиум салон красоты, предлагающий широкий спектр услуг',
                    ],
                ],
                'latitude' => 50.45,
                'longitude' => 30.5, // Kyiv coordinates
                'verified' => true,
            ],
            [
                'name' => 'Nail Studio',
                'slug' => 'nail-studio',
                'address' => 'вул. Дерибасівська, 7',
                'city' => 'Одеса',
                'phone' => '+380487654321',
                'email' => 'info@nailstudio.ua',
                'website' => 'https://nailstudio.ua',
                'social_links' => [
                    'instagram' => 'nail_studio_ua',
                    'facebook' => 'nailstudio.ua',
                ],
                'working_hours' => [
                    'monday' => ['10:00', '19:00'],
                    'tuesday' => ['10:00', '19:00'],
                    'wednesday' => ['10:00', '19:00'],
                    'thursday' => ['10:00', '19:00'],
                    'friday' => ['10:00', '20:00'],
                    'saturday' => ['11:00', '18:00'],
                    'sunday' => ['11:00', '16:00'],
                ],
                'translations' => [
                    'description' => [
                        'en' => 'Specialized nail salon focusing on manicure and pedicure services',
                        'uk' => 'Спеціалізований салон нігтів, що фокусується на послугах манікюру та педикюру',
                        'ru' => 'Специализированный салон ногтей, фокусирующийся на услугах маникюра и педикюра',
                    ],
                ],
                'latitude' => 46.48,
                'longitude' => 30.73, // Odesa coordinates
                'verified' => true,
            ],
            [
                'name' => 'Hair Masters',
                'slug' => 'hair-masters',
                'address' => 'вул. Соборна, 23',
                'city' => 'Львів',
                'phone' => '+380327890123',
                'email' => 'info@hairmasters.ua',
                'website' => 'https://hairmasters.ua',
                'social_links' => [
                    'instagram' => 'hair_masters_ua',
                    'facebook' => 'hairmasters.ua',
                ],
                'working_hours' => [
                    'monday' => ['09:00', '19:00'],
                    'tuesday' => ['09:00', '19:00'],
                    'wednesday' => ['09:00', '19:00'],
                    'thursday' => ['09:00', '19:00'],
                    'friday' => ['09:00', '19:00'],
                    'saturday' => ['10:00', '17:00'],
                    'sunday' => ['closed'],
                ],
                'translations' => [
                    'description' => [
                        'en' => 'Expert hair salon with experienced stylists',
                        'uk' => 'Експертний салон волосся з досвідченими стилістами',
                        'ru' => 'Экспертный салон волос с опытными стилистами',
                    ],
                ],
                'latitude' => 49.84,
                'longitude' => 24.03, // Lviv coordinates
                'verified' => true,
            ],
        ];

        foreach ($salons as $salonData) {
            // Store location data separately
            $latitude = $salonData['latitude'];
            $longitude = $salonData['longitude'];
            unset($salonData['latitude']);
            unset($salonData['longitude']);

            // Encode JSON fields
            $salonData['owner_id'] = $owner->id;
            $salonData['social_links'] = json_encode($salonData['social_links']);
            $salonData['working_hours'] = json_encode($salonData['working_hours']);
            $salonData['translations'] = json_encode($salonData['translations']);
            $salonData['active'] = true;

            // Create salon without location first
            $salon = Salon::create($salonData);

            // Set location using raw SQL that properly casts the data type
            DB::statement(
                "UPDATE salons SET location = point(?, ?) WHERE id = ?",
                [$longitude, $latitude, $salon->id]
            );
        }
    }
}
