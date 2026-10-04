<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Business Details
            'business.name' => ['value' => 'The Drive Clinic', 'group' => 'business'],
            'business.tagline' => ['value' => "Your Car's Healthcare Centre", 'group' => 'business'],
            'business.address' => ['value' => '[Nanak Nagar, Jammu, J&K 180004]', 'group' => 'business'],
            'business.phone' => ['value' => '[+91 94191 00000]', 'group' => 'business'],
            'business.whatsapp' => ['value' => '919419100000', 'group' => 'business'],
            'business.email' => ['value' => 'contact@thedriveclinic.in', 'group' => 'business'],
            'business.google_review_url' => ['value' => 'https://g.page/r/thedriveclinic/review', 'group' => 'business'],
            'business.map_coordinates' => ['value' => '32.7050, 74.8720', 'group' => 'business'],

            // Capacity & Hours
            'capacity.bays' => ['value' => 3, 'group' => 'capacity'],
            'capacity.slot_length_minutes' => ['value' => 30, 'group' => 'capacity'],
            'capacity.opening_hours' => [
                'value' => [
                    'monday'    => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                    'tuesday'   => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                    'wednesday' => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                    'thursday'  => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                    'friday'    => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                    'saturday'  => ['open' => '09:00', 'close' => '20:00', 'closed' => false],
                    'sunday'    => ['open' => '09:00', 'close' => '20:00', 'closed' => false],
                ],
                'group' => 'capacity'
            ],
            'capacity.blocked_dates' => ['value' => [], 'group' => 'capacity'],

            // Billing & GST
            'billing.invoice_prefix' => ['value' => 'TDC', 'group' => 'billing'],
            'billing.invoice_format' => ['value' => 'TDC/{FY}/{SEQ4}', 'group' => 'billing'],
            'billing.gst_enabled' => ['value' => false, 'group' => 'billing'],
            'billing.gstin' => ['value' => '[01AAAAA0000A1Z5]', 'group' => 'billing'],
            'billing.cgst_rate' => ['value' => 9.0, 'group' => 'billing'],
            'billing.sgst_rate' => ['value' => 9.0, 'group' => 'billing'],
            'billing.max_staff_discount_percent' => ['value' => 5, 'group' => 'billing'],

            // Digital Car Health Check
            'health_check.weights' => [
                'value' => [
                    'exterior' => 30,
                    'interior' => 30,
                    'wheels' => 15,
                    'glass' => 15,
                    'protection' => 10,
                ],
                'group' => 'health_check'
            ],
            'health_check.disclaimer' => [
                'value' => 'The Drive Health Score describes cosmetic condition only. It is not a certified mechanical, roadworthiness or safety inspection.',
                'group' => 'health_check'
            ],

            // Website Content & Announcement
            'website.announcement_bar_text' => ['value' => 'Grand Opening in Nanak Nagar, Jammu! Get a Free Digital Car Health Check.', 'group' => 'website'],
            'website.announcement_bar_link' => ['value' => '/free-car-health-check', 'group' => 'website'],
            'website.announcement_bar_active' => ['value' => true, 'group' => 'website'],

            // Feature Flags
            'features.passport_public_view' => ['value' => false, 'group' => 'features'],
        ];

        foreach ($settings as $key => $data) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $data['value'], 'group' => $data['group']]
            );
        }
    }
}
