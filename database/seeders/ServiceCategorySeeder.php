<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Foam Wash & Decontamination',
                'slug' => 'wash',
                'description' => 'Surgical 2-bucket foam washes with pH-neutral shampoos and safe touch methods.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Paint Correction & Detailing',
                'slug' => 'detailing',
                'description' => 'Multi-stage machine compounding and polishing to eliminate swirls, scratches and oxidation.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Ceramic & Graphene Protection',
                'slug' => 'protection',
                'description' => 'Permanent nano-ceramic and graphene shields offering extreme hydrophobicity and UV resistance.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Interior Deep Sanitization',
                'slug' => 'interior',
                'description' => 'Hot-water extraction, antimicrobial steam sterilization and leather rejuvenation.',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $cat) {
            ServiceCategory::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
