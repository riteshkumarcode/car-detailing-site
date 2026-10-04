<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\MembershipPlan;
use Illuminate\Database\Seeder;

class MembershipPlanSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::first();

        $plans = [
            [
                'branch_id' => $branch?->id,
                'name' => 'Essential Care Plan',
                'slug' => 'essential',
                'price' => 1999.00,
                'billing_frequency' => 'yearly',
                'duration_days' => 365,
                'included_services' => [
                    ['service_id' => 1, 'service_name' => 'Essential Foam Wash', 'count' => 12],
                    ['service_id' => 6, 'service_name' => 'Engine Bay Decontamination', 'count' => 1],
                ],
                'category_discounts' => [
                    'detailing' => 10,
                    'protection' => 5,
                    'interior' => 10,
                ],
                'features' => [
                    '12 x Essential Foam Washes per year',
                    '1 x Engine Bay Steam Clean',
                    '10% off all Detailing & Interior services',
                    'Priority slot booking on weekends',
                    'Digital Car Passport maintenance logging',
                ],
                'benefits_description' => 'Ideal for daily commuters wanting reliable, swirl-free monthly washes at unbeatable value.',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'branch_id' => $branch?->id,
                'name' => 'Premium Healthcare Plan',
                'slug' => 'premium',
                'price' => 4999.00,
                'billing_frequency' => 'yearly',
                'duration_days' => 365,
                'included_services' => [
                    ['service_id' => 1, 'service_name' => 'Essential Foam Wash', 'count' => 18],
                    ['service_id' => 2, 'service_name' => 'Interior Deep Sanitization', 'count' => 2],
                    ['service_id' => 6, 'service_name' => 'Engine Bay Decontamination', 'count' => 2],
                ],
                'category_discounts' => [
                    'detailing' => 15,
                    'protection' => 10,
                    'interior' => 15,
                ],
                'features' => [
                    '18 x Essential Foam Washes per year',
                    '2 x Complete Interior Deep Cleans',
                    '2 x Engine Bay Decontamination treatments',
                    '15% off all Paint Correction & Detailing',
                    '10% off Ceramic Coating packages',
                    'Priority bay dispatch on arrival',
                    'Dedicated assigned technician',
                ],
                'benefits_description' => 'Our most popular plan. Comprehensive bumper-to-bumper healthcare for family cars and luxury vehicles.',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'branch_id' => $branch?->id,
                'name' => 'Elite Protection Concierge',
                'slug' => 'elite',
                'price' => 9999.00,
                'billing_frequency' => 'yearly',
                'duration_days' => 365,
                'included_services' => [
                    ['service_id' => 1, 'service_name' => 'Essential Foam Wash', 'count' => 26],
                    ['service_id' => 2, 'service_name' => 'Interior Deep Sanitization', 'count' => 4],
                    ['service_id' => 3, 'service_name' => 'Paint Correction & Gloss Restoration', 'count' => 1],
                    ['service_id' => 6, 'service_name' => 'Engine Bay Decontamination', 'count' => 4],
                ],
                'category_discounts' => [
                    'detailing' => 20,
                    'protection' => 15,
                    'interior' => 20,
                ],
                'features' => [
                    '26 x Bi-weekly Essential Foam Washes',
                    '4 x Quarterly Interior Deep Cleans',
                    '1 x Annual 2-Stage Machine Paint Correction',
                    '4 x Engine Bay Steam Cleans',
                    '20% off all additional studio services',
                    'Guaranteed immediate bay access',
                    'VIP Concierge health check report before every long trip',
                ],
                'benefits_description' => 'Uncompromising preservation for luxury, exotic and collector vehicles in Jammu.',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            MembershipPlan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
