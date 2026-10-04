<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::first();

        $reviews = [
            [
                'branch_id' => $branch?->id,
                'customer_name' => 'Vikramaditya S.',
                'vehicle_model' => 'Mahindra Thar (Black)',
                'service_availed' => 'Paint Correction & Ceramic Coating',
                'rating' => 5,
                'review_text' => 'Brought my Thar with severe swirl marks from previous careless washes. The Drive Clinic team ran a complete diagnosis with depth gauges, restored the black paint to mirror finish, and applied 9H ceramic. The transparency and Digital Passport updates are game-changing in Jammu.',
                'location' => 'Gandhi Nagar, Jammu',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'branch_id' => $branch?->id,
                'customer_name' => 'Dr. Pooja Sharma',
                'vehicle_model' => 'Hyundai Creta',
                'service_availed' => 'Interior Deep Sanitization',
                'rating' => 5,
                'review_text' => 'With two young kids, our Creta seats had persistent food and juice stains. The dry steam and extraction cleaning made the interior look and smell brand new. The health check report on WhatsApp was super detailed.',
                'location' => 'Nanak Nagar, Jammu',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'branch_id' => $branch?->id,
                'customer_name' => 'Amitabh Khajuria',
                'vehicle_model' => 'BMW 3 Series (Estoril Blue)',
                'service_availed' => 'Drive Club Premium Member',
                'rating' => 5,
                'review_text' => 'Finally a car studio in Jammu that understands 2-bucket wash techniques and doesn\'t use dirty rags. The staff are well-trained, bays are immaculate, and the Drive Club plan saves a lot of money.',
                'location' => 'Channi Himmat, Jammu',
                'is_featured' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($reviews as $rev) {
            Testimonial::updateOrCreate(['customer_name' => $rev['customer_name']], $rev);
        }
    }
}
