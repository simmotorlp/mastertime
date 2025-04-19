<?php

namespace Database\Seeders;

use App\Models\Salon;
use App\Models\Specialist;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class SpecialistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get specialist user
        $specialistUser = User::where('email', 'specialist@mastertime.ua')->first();

        if (!$specialistUser) {
            $specialistRole = Role::where('name', 'specialist')->first();
            $specialistUser = User::factory()->create([
                'name' => 'Beauty Specialist',
                'email' => 'specialist@mastertime.ua',
                'role_id' => $specialistRole->id,
            ]);
        }

        // Get salons
        $salons = Salon::all();

        if ($salons->isEmpty()) {
            $this->command->info('No salons found. Please run SalonSeeder first.');
            return;
        }

        // Define specialists
        $specialists = [
            [
                'name' => 'Olena Petrenko',
                'position' => 'Hair Stylist',
                'translations' => [
                    'bio' => [
                        'en' => 'Professional hair stylist with 5 years of experience in cutting, coloring, and styling.',
                        'uk' => 'Професійний стиліст з 5-річним досвідом стрижки, фарбування та укладання волосся.',
                        'ru' => 'Профессиональный стилист с 5-летним опытом стрижки, окрашивания и укладки волос.',
                    ],
                    'position' => [
                        'en' => 'Hair Stylist',
                        'uk' => 'Стиліст з волосся',
                        'ru' => 'Стилист по волосам',
                    ],
                ],
                'avatar' => 'specialists/olena.jpg',
                'working_hours' => [
                    'monday' => ['09:00', '18:00'],
                    'tuesday' => ['09:00', '18:00'],
                    'wednesday' => ['09:00', '18:00'],
                    'thursday' => ['09:00', '18:00'],
                    'friday' => ['09:00', '18:00'],
                    'saturday' => ['10:00', '15:00'],
                    'sunday' => ['closed'],
                ],
                'user_id' => $specialistUser->id,
                'salon_id' => $salons[0]->id,
            ],
            [
                'name' => 'Mariya Kovalenko',
                'position' => 'Nail Technician',
                'translations' => [
                    'bio' => [
                        'en' => 'Certified nail technician specializing in gel extensions and nail art.',
                        'uk' => 'Сертифікований майстер нігтьового сервісу, спеціалізується на гелевому нарощуванні та нейл-арті.',
                        'ru' => 'Сертифицированный мастер ногтевого сервиса, специализируется на гелевом наращивании и нейл-арте.',
                    ],
                    'position' => [
                        'en' => 'Nail Technician',
                        'uk' => 'Майстер манікюру',
                        'ru' => 'Мастер маникюра',
                    ],
                ],
                'avatar' => 'specialists/mariya.jpg',
                'working_hours' => [
                    'monday' => ['10:00', '19:00'],
                    'tuesday' => ['10:00', '19:00'],
                    'wednesday' => ['10:00', '19:00'],
                    'thursday' => ['10:00', '19:00'],
                    'friday' => ['10:00', '19:00'],
                    'saturday' => ['11:00', '16:00'],
                    'sunday' => ['closed'],
                ],
                'user_id' => null,
                'salon_id' => $salons[1]->id,
            ],
            [
                'name' => 'Iryna Shevchenko',
                'position' => 'Makeup Artist',
                'translations' => [
                    'bio' => [
                        'en' => 'Makeup artist with experience in bridal, photoshoot, and everyday makeup.',
                        'uk' => 'Візажист з досвідом у весільному, фотосесійному та повсякденному макіяжі.',
                        'ru' => 'Визажист с опытом в свадебном, фотосессионном и повседневном макияже.',
                    ],
                    'position' => [
                        'en' => 'Makeup Artist',
                        'uk' => 'Візажист',
                        'ru' => 'Визажист',
                    ],
                ],
                'avatar' => 'specialists/iryna.jpg',
                'working_hours' => [
                    'monday' => ['09:00', '17:00'],
                    'tuesday' => ['09:00', '17:00'],
                    'wednesday' => ['09:00', '17:00'],
                    'thursday' => ['09:00', '17:00'],
                    'friday' => ['09:00', '17:00'],
                    'saturday' => ['10:00', '15:00'],
                    'sunday' => ['closed'],
                ],
                'user_id' => null,
                'salon_id' => $salons[0]->id,
            ],
            [
                'name' => 'Natalia Morozova',
                'position' => 'Lash Artist',
                'translations' => [
                    'bio' => [
                        'en' => 'Expert in lash extensions and lash lifts with attention to detail.',
                        'uk' => 'Експерт з нарощування вій та ламінування з увагою до деталей.',
                        'ru' => 'Эксперт по наращиванию ресниц и ламинированию с вниманием к деталям.',
                    ],
                    'position' => [
                        'en' => 'Lash Artist',
                        'uk' => 'Майстер з нарощування вій',
                        'ru' => 'Мастер по наращиванию ресниц',
                    ],
                ],
                'avatar' => 'specialists/natalia.jpg',
                'working_hours' => [
                    'monday' => ['10:00', '19:00'],
                    'tuesday' => ['10:00', '19:00'],
                    'wednesday' => ['10:00', '19:00'],
                    'thursday' => ['10:00', '19:00'],
                    'friday' => ['10:00', '19:00'],
                    'saturday' => ['11:00', '17:00'],
                    'sunday' => ['closed'],
                ],
                'user_id' => null,
                'salon_id' => $salons[1]->id,
            ],
            [
                'name' => 'Sergiy Kravchuk',
                'position' => 'Barber',
                'translations' => [
                    'bio' => [
                        'en' => 'Professional barber specializing in men\'s haircuts and beard styling.',
                        'uk' => 'Професійний барбер, що спеціалізується на чоловічих стрижках та укладанні бороди.',
                        'ru' => 'Профессиональный барбер, специализирующийся на мужских стрижках и укладке бороды.',
                    ],
                    'position' => [
                        'en' => 'Barber',
                        'uk' => 'Барбер',
                        'ru' => 'Барбер',
                    ],
                ],
                'avatar' => 'specialists/sergiy.jpg',
                'working_hours' => [
                    'monday' => ['09:00', '18:00'],
                    'tuesday' => ['09:00', '18:00'],
                    'wednesday' => ['09:00', '18:00'],
                    'thursday' => ['09:00', '18:00'],
                    'friday' => ['09:00', '18:00'],
                    'saturday' => ['10:00', '16:00'],
                    'sunday' => ['closed'],
                ],
                'user_id' => null,
                'salon_id' => $salons[2]->id,
            ],
            [
                'name' => 'Anna Lysenko',
                'position' => 'Cosmetologist',
                'translations' => [
                    'bio' => [
                        'en' => 'Licensed cosmetologist providing facial treatments and skincare consultations.',
                        'uk' => 'Ліцензований косметолог, що надає процедури для обличчя та консультації по догляду за шкірою.',
                        'ru' => 'Лицензированный косметолог, предоставляющий процедуры для лица и консультации по уходу за кожей.',
                    ],
                    'position' => [
                        'en' => 'Cosmetologist',
                        'uk' => 'Косметолог',
                        'ru' => 'Косметолог',
                    ],
                ],
                'avatar' => 'specialists/anna.jpg',
                'working_hours' => [
                    'monday' => ['10:00', '19:00'],
                    'tuesday' => ['10:00', '19:00'],
                    'wednesday' => ['10:00', '19:00'],
                    'thursday' => ['10:00', '19:00'],
                    'friday' => ['10:00', '19:00'],
                    'saturday' => ['11:00', '16:00'],
                    'sunday' => ['closed'],
                ],
                'user_id' => null,
                'salon_id' => $salons[0]->id,
            ],
        ];

        // Create specialists
        foreach ($specialists as $specialistData) {
            // Convert array fields to JSON
            $specialistData['translations'] = json_encode($specialistData['translations']);
            $specialistData['working_hours'] = json_encode($specialistData['working_hours']);
            $specialistData['active'] = true;

            // Create or update specialist
            $specialist = Specialist::updateOrCreate(
                ['name' => $specialistData['name'], 'salon_id' => $specialistData['salon_id']],
                $specialistData
            );
        }
    }
}
