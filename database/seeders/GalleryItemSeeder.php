<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\GalleryItem;
use Illuminate\Database\Seeder;

class GalleryItemSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::first();

        $items = [
            [
                'branch_id' => $branch?->id,
                'title' => 'Severe Swirl Marks & Wash Scratches Removal',
                'area' => 'paint',
                'problem_description' => 'Heavy spiderweb swirls and wash marring from daily roadside wiping.',
                'service_name' => '2-Stage Machine Paint Correction',
                'car_model' => 'Mahindra Thar 4x4 (Napoli Black)',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'branch_id' => $branch?->id,
                'title' => 'Fabric Stains & Coffee Spill Extraction',
                'area' => 'interior',
                'problem_description' => 'Dried coffee spills, mud stains, and discoloured beige fabric upholstery.',
                'service_name' => 'Interior Deep Sanitization & Extraction',
                'car_model' => 'Hyundai Creta SX',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'branch_id' => $branch?->id,
                'title' => 'Severe Brake Dust & Iron Fallout Elimination',
                'area' => 'wheels',
                'problem_description' => 'Baked-in metallic brake dust and road grime on diamond-cut alloys.',
                'service_name' => 'Alloy Wheel Chemical Decontamination',
                'car_model' => 'BMW 3 Series M Sport',
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'branch_id' => $branch?->id,
                'title' => 'Hard Water Etching & Smear Removal',
                'area' => 'glass',
                'problem_description' => 'Severe mineral deposits and wiper trails causing night driving glare.',
                'service_name' => 'Glass Polishing & Hydrophobic Repellent',
                'car_model' => 'Kia Seltos',
                'is_featured' => true,
                'sort_order' => 4,
            ],
            [
                'branch_id' => $branch?->id,
                'title' => 'Oxidized Red Clear Coat Deep Mirror Gloss',
                'area' => 'paint',
                'problem_description' => 'Faded, dull paint suffering from 4 years of sun exposure.',
                'service_name' => 'Paint Jewelling & 9H Graphene Ceramic',
                'car_model' => 'Volkswagen Polo GT (Flash Red)',
                'is_featured' => true,
                'sort_order' => 5,
            ],
            [
                'branch_id' => $branch?->id,
                'title' => 'Underbody Mud Cake & Rust Prevention',
                'area' => 'exterior',
                'problem_description' => 'Dried salt and clay cake on chassis rails and wheel arches.',
                'service_name' => 'Underbody Anti-Rust Coating',
                'car_model' => 'Toyota Fortuner 4x4',
                'is_featured' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($items as $item) {
            GalleryItem::updateOrCreate(['title' => $item['title']], $item);
        }
    }
}
