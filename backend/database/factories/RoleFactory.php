<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Role;

class RoleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'translations' => json_encode([
                'en' => fake()->word(),
                'uk' => fake()->word(),
            ]),
            'guard_name' => 'web',
        ];
    }
}
