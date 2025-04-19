<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ServiceCategorySeeder::class,
            SalonSeeder::class,
            SpecialistSeeder::class,
            ServiceSeeder::class,
            AppointmentSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
