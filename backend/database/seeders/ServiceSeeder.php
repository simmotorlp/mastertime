<?php

namespace Database\Seeders;

use App\Models\Salon;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Specialist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories
        $categories = ServiceCategory::all();

        if ($categories->isEmpty()) {
            $this->command->info('No service categories found. Please run ServiceCategorySeeder first.');
            return;
        }

        // Get salons
        $salons = Salon::all();

        if ($salons->isEmpty()) {
            $this->command->info('No salons found. Please run SalonSeeder first.');
            return;
        }

        // Map category names to objects for easier lookup
        $categoryMap = [];
        foreach ($categories as $category) {
            $categoryMap[$category->name] = $category;
        }

        // Define services per salon
        $services = [
            // Beauty Haven salon services
            'Beauty Haven' => [
                [
                    'name' => 'Women\'s Haircut',
                    'category' => 'Hair',
                    'price' => 500,
                    'duration' => 60,
                    'translations' => [
                        'description' => [
                            'en' => 'Professional haircut for women including consultation, wash, cut, and style.',
                            'uk' => 'Професійна стрижка для жінок, включаючи консультацію, миття, стрижку та укладку.',
                            'ru' => 'Профессиональная стрижка для женщин, включая консультацию, мытье, стрижку и укладку.',
                        ],
                    ],
                ],
                [
                    'name' => 'Men\'s Haircut',
                    'category' => 'Hair',
                    'price' => 400,
                    'duration' => 45,
                    'translations' => [
                        'description' => [
                            'en' => 'Men\'s haircut including consultation, wash, cut, and style.',
                            'uk' => 'Чоловіча стрижка, включаючи консультацію, миття, стрижку та укладку.',
                            'ru' => 'Мужская стрижка, включая консультацию, мытье, стрижку и укладку.',
                        ],
                    ],
                ],
                [
                    'name' => 'Hair Coloring',
                    'category' => 'Hair',
                    'price' => 1200,
                    'duration' => 120,
                    'translations' => [
                        'description' => [
                            'en' => 'Professional hair coloring service with premium products.',
                            'uk' => 'Професійне фарбування волосся з преміум продуктами.',
                            'ru' => 'Профессиональное окрашивание волос с премиум продуктами.',
                        ],
                    ],
                ],
                [
                    'name' => 'Makeup Application',
                    'category' => 'Makeup',
                    'price' => 800,
                    'duration' => 60,
                    'translations' => [
                        'description' => [
                            'en' => 'Professional makeup application for any occasion.',
                            'uk' => 'Професійне нанесення макіяжу для будь-якого випадку.',
                            'ru' => 'Профессиональное нанесение макияжа для любого случая.',
                        ],
                    ],
                ],
                [
                    'name' => 'Bridal Makeup',
                    'category' => 'Makeup',
                    'price' => 1500,
                    'duration' => 90,
                    'translations' => [
                        'description' => [
                            'en' => 'Specialized makeup for brides including trial and wedding day application.',
                            'uk' => 'Спеціалізований макіяж для наречених, включаючи пробний та весільний макіяж.',
                            'ru' => 'Специализированный макияж для невест, включая пробный и свадебный макияж.',
                        ],
                    ],
                ],
                [
                    'name' => 'Facial Treatment',
                    'category' => 'Face',
                    'price' => 1000,
                    'duration' => 60,
                    'translations' => [
                        'description' => [
                            'en' => 'Rejuvenating facial treatment including cleansing, exfoliation, and mask.',
                            'uk' => 'Омолоджуюча процедура для обличчя, що включає очищення, пілінг та маску.',
                            'ru' => 'Омолаживающая процедура для лица, включающая очищение, пилинг и маску.',
                        ],
                    ],
                ],
            ],

            // Nail Studio salon services
            'Nail Studio' => [
                [
                    'name' => 'Classic Manicure',
                    'category' => 'Nails',
                    'price' => 350,
                    'duration' => 40,
                    'translations' => [
                        'description' => [
                            'en' => 'Classic manicure including nail shaping, cuticle care, and polish.',
                            'uk' => 'Класичний манікюр, включаючи формування нігтів, догляд за кутикулою та покриття лаком.',
                            'ru' => 'Классический маникюр, включая формирование ногтей, уход за кутикулой и покрытие лаком.',
                        ],
                    ],
                ],
                [
                    'name' => 'Gel Manicure',
                    'category' => 'Nails',
                    'price' => 550,
                    'duration' => 60,
                    'translations' => [
                        'description' => [
                            'en' => 'Gel manicure with long-lasting gel polish that stays perfect for up to 2 weeks.',
                            'uk' => 'Гель-манікюр з довготривалим гель-лаком, який залишається ідеальним до 2 тижнів.',
                            'ru' => 'Гель-маникюр с долговременным гель-лаком, который остается идеальным до 2 недель.',
                        ],
                    ],
                ],
                [
                    'name' => 'Classic Pedicure',
                    'category' => 'Nails',
                    'price' => 450,
                    'duration' => 50,
                    'translations' => [
                        'description' => [
                            'en' => 'Classic pedicure including foot soak, exfoliation, nail shaping, and polish.',
                            'uk' => 'Класичний педикюр, включаючи замочування ніг, пілінг, формування нігтів та покриття лаком.',
                            'ru' => 'Классический педикюр, включая замачивание ног, пилинг, формирование ногтей и покрытие лаком.',
                        ],
                    ],
                ],
                [
                    'name' => 'Nail Art',
                    'category' => 'Nails',
                    'price' => 200,
                    'duration' => 30,
                    'translations' => [
                        'description' => [
                            'en' => 'Creative nail art designs for any occasion.',
                            'uk' => 'Креативні дизайни нігтів для будь-якого випадку.',
                            'ru' => 'Креативные дизайны ногтей для любого случая.',
                        ],
                    ],
                ],
                [
                    'name' => 'Lash Extensions',
                    'category' => 'Eyebrows & Lashes',
                    'price' => 1200,
                    'duration' => 120,
                    'translations' => [
                        'description' => [
                            'en' => 'Full set of classic eyelash extensions.',
                            'uk' => 'Повний набір класичного нарощування вій.',
                            'ru' => 'Полный набор классического наращивания ресниц.',
                        ],
                    ],
                ],
            ],

            // Hair Masters salon services
            'Hair Masters' => [
                [
                    'name' => 'Beard Trim',
                    'category' => 'Hair',
                    'price' => 250,
                    'duration' => 30,
                    'translations' => [
                        'description' => [
                            'en' => 'Professional beard trimming and styling.',
                            'uk' => 'Професійне підстригання та укладання бороди.',
                            'ru' => 'Профессиональная стрижка и укладка бороды.',
                        ],
                    ],
                ],
                [
                    'name' => 'Hair and Beard Combo',
                    'category' => 'Hair',
                    'price' => 600,
                    'duration' => 75,
                    'translations' => [
                        'description' => [
                            'en' => 'Men\'s haircut and beard trim combo.',
                            'uk' => 'Комбо-пакет чоловічої стрижки та підстригання бороди.',
                            'ru' => 'Комбо-пакет мужской стрижки и подстригания бороды.',
                        ],
                    ],
                ],
                [
                    'name' => 'Women\'s Blowout',
                    'category' => 'Hair',
                    'price' => 450,
                    'duration' => 45,
                    'translations' => [
                        'description' => [
                            'en' => 'Professional hair washing and blowout for a perfect style.',
                            'uk' => 'Професійне миття та укладання волосся для ідеального стилю.',
                            'ru' => 'Профессиональное мытье и укладка волос для идеального стиля.',
                        ],
                    ],
                ],
                [
                    'name' => 'Balayage',
                    'category' => 'Hair',
                    'price' => 1800,
                    'duration' => 180,
                    'translations' => [
                        'description' => [
                            'en' => 'Hand-painted highlights creating a natural gradient of color.',
                            'uk' => 'Ручне фарбування волосся, що створює природний градієнт кольору.',
                            'ru' => 'Ручное окрашивание волос, создающее естественный градиент цвета.',
                        ],
                    ],
                ],
            ],
        ];

        // Create services for each salon
        foreach ($services as $salonName => $salonServices) {
            $salon = $salons->where('name', $salonName)->first();

            if (!$salon) {
                $this->command->info("Salon '$salonName' not found. Skipping its services.");
                continue;
            }

            // Get specialists for this salon
            $specialists = $salon->specialists;

            foreach ($salonServices as $serviceData) {
                // Get category ID
                $category = $categoryMap[$serviceData['category']] ?? null;

                if (!$category) {
                    $this->command->info("Category '{$serviceData['category']}' not found. Skipping '{$serviceData['name']}' service.");
                    continue;
                }

                // Create service data
                $data = [
                    'salon_id' => $salon->id,
                    'category_id' => $category->id,
                    'name' => $serviceData['name'],
                    'translations' => json_encode($serviceData['translations']),
                    'price' => $serviceData['price'],
                    'duration' => $serviceData['duration'],
                    'active' => true,
                ];

                // Create or update service
                $service = Service::updateOrCreate(
                    ['salon_id' => $salon->id, 'name' => $serviceData['name']],
                    $data
                );

                // Add specialists to this service
                if ($specialists->isNotEmpty()) {
                    // First, check if this service category matches the specialist's expertise
                    // For simplicity, we'll assume all specialists can provide all services in their salon
                    foreach ($specialists as $specialist) {
                        // Check if the relationship already exists
                        $exists = DB::table('specialist_service')
                            ->where('specialist_id', $specialist->id)
                            ->where('service_id', $service->id)
                            ->exists();

                        // Only insert if the relationship doesn't exist yet
                        if (!$exists) {
                            DB::table('specialist_service')->insert([
                                'specialist_id' => $specialist->id,
                                'service_id' => $service->id,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
