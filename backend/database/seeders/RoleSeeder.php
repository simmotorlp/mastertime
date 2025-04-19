<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'translations' => json_encode([
                    'en' => 'Administrator',
                    'uk' => 'Адміністратор',
                    'ru' => 'Администратор',
                ]),
                'guard_name' => 'web',
            ],
            [
                'name' => 'salon_owner',
                'translations' => json_encode([
                    'en' => 'Salon Owner',
                    'uk' => 'Власник салону',
                    'ru' => 'Владелец салона',
                ]),
                'guard_name' => 'web',
            ],
            [
                'name' => 'specialist',
                'translations' => json_encode([
                    'en' => 'Specialist',
                    'uk' => 'Спеціаліст',
                    'ru' => 'Специалист',
                ]),
                'guard_name' => 'web',
            ],
            [
                'name' => 'client',
                'translations' => json_encode([
                    'en' => 'Client',
                    'uk' => 'Клієнт',
                    'ru' => 'Клиент',
                ]),
                'guard_name' => 'web',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
