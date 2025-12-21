<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Format;
use App\Models\SupportType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ReferentialSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSupportTypesAndFormats();
        $this->seedCategories();
    }

    private function seedSupportTypesAndFormats(): void
    {
        $supportTypes = [
            [
                'name' => 'Flyer',
                'formats' => ['A5', 'A4', 'DL', 'Carré'],
            ],
            [
                'name' => 'Carte de visite',
                'formats' => ['Standard (85x55mm)'],
            ],
            [
                'name' => 'Mailing postal',
                'formats' => ['A5', 'A4', 'DL'],
            ],
            [
                'name' => 'Panneau',
                'formats' => ['Standard', 'XXL'],
            ],
            [
                'name' => 'Roll-up',
                'formats' => ['85x200cm', '100x200cm'],
            ],
        ];

        foreach ($supportTypes as $index => $type) {
            $supportType = SupportType::create([
                'name' => $type['name'],
                'slug' => Str::slug($type['name']),
                'is_active' => true,
                'sort_order' => $index,
            ]);

            foreach ($type['formats'] as $formatIndex => $formatName) {
                Format::create([
                    'support_type_id' => $supportType->id,
                    'name' => $formatName,
                    'slug' => Str::slug($formatName),
                    'is_active' => true,
                    'sort_order' => $formatIndex,
                ]);
            }
        }
    }

    private function seedCategories(): void
    {
        $categories = [
            'Prospection',
            'Estimation',
            'Just Listed',
            'Just Sold',
            'Événement',
            'Vitrine',
            'Boîtage',
            'Standard',
            'Premium',
        ];

        foreach ($categories as $index => $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'is_active' => true,
                'sort_order' => $index,
            ]);
        }
    }
}
