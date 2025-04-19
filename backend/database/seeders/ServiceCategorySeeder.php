<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Hair',
                'translations' => [
                    'description' => [
                        'en' => 'Hair styling, cutting, coloring and treatment services',
                        'uk' => 'Послуги укладання, стрижки, фарбування та догляду за волоссям',
                        'ru' => 'Услуги укладки, стрижки, окрашивания и ухода за волосами',
                    ],
                    'name' => [
                        'en' => 'Hair',
                        'uk' => 'Волосся',
                        'ru' => 'Волосы',
                    ],
                ],
                'icon' => 'fa-scissors',
                'order' => 1,
            ],
            [
                'name' => 'Nails',
                'translations' => [
                    'description' => [
                        'en' => 'Manicure, pedicure, nail extensions and nail art',
                        'uk' => 'Манікюр, педикюр, нарощування нігтів та нейл-арт',
                        'ru' => 'Маникюр, педикюр, наращивание ногтей и нейл-арт',
                    ],
                    'name' => [
                        'en' => 'Nails',
                        'uk' => 'Нігті',
                        'ru' => 'Ногти',
                    ],
                ],
                'icon' => 'fa-hand-sparkles',
                'order' => 2,
            ],
            [
                'name' => 'Makeup',
                'translations' => [
                    'description' => [
                        'en' => 'Professional makeup services for all occasions',
                        'uk' => 'Професійні послуги макіяжу для всіх випадків',
                        'ru' => 'Профессиональные услуги макияжа для всех случаев',
                    ],
                    'name' => [
                        'en' => 'Makeup',
                        'uk' => 'Макіяж',
                        'ru' => 'Макияж',
                    ],
                ],
                'icon' => 'fa-eye',
                'order' => 3,
            ],
            [
                'name' => 'Massage',
                'translations' => [
                    'description' => [
                        'en' => 'Relaxing and therapeutic massage services',
                        'uk' => 'Розслаблюючі та терапевтичні послуги масажу',
                        'ru' => 'Расслабляющие и терапевтические услуги массажа',
                    ],
                    'name' => [
                        'en' => 'Massage',
                        'uk' => 'Масаж',
                        'ru' => 'Массаж',
                    ],
                ],
                'icon' => 'fa-hands',
                'order' => 4,
            ],
            [
                'name' => 'Face',
                'translations' => [
                    'description' => [
                        'en' => 'Facial treatments and skincare services',
                        'uk' => 'Процедури для обличчя та послуги догляду за шкірою',
                        'ru' => 'Процедуры для лица и услуги по уходу за кожей',
                    ],
                    'name' => [
                        'en' => 'Face',
                        'uk' => 'Обличчя',
                        'ru' => 'Лицо',
                    ],
                ],
                'icon' => 'fa-face-smile',
                'order' => 5,
            ],
            [
                'name' => 'Body',
                'translations' => [
                    'description' => [
                        'en' => 'Body treatments, waxing, and skincare',
                        'uk' => 'Процедури для тіла, восковая епіляція та догляд за шкірою',
                        'ru' => 'Процедуры для тела, восковая эпиляция и уход за кожей',
                    ],
                    'name' => [
                        'en' => 'Body',
                        'uk' => 'Тіло',
                        'ru' => 'Тело',
                    ],
                ],
                'icon' => 'fa-child',
                'order' => 6,
            ],
            [
                'name' => 'Eyebrows & Lashes',
                'translations' => [
                    'description' => [
                        'en' => 'Eyebrow styling, tinting, and eyelash extensions',
                        'uk' => 'Стилізація брів, фарбування та нарощування вій',
                        'ru' => 'Стилизация бровей, окрашивание и наращивание ресниц',
                    ],
                    'name' => [
                        'en' => 'Eyebrows & Lashes',
                        'uk' => 'Брови і вії',
                        'ru' => 'Брови и ресницы',
                    ],
                ],
                'icon' => 'fa-eye-lash',
                'order' => 7,
            ],
        ];

        foreach ($categories as $category) {
            ServiceCategory::updateOrCreate(
                ['name' => $category['name']],
                [
                    'name' => $category['name'],
                    'translations' => json_encode($category['translations']),
                    'slug' => Str::slug($category['name']),
                    'icon' => $category['icon'],
                    'order' => $category['order'],
                ]
            );
        }
    }
}
