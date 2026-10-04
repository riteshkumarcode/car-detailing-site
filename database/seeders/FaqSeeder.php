<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'category' => 'general',
                'question' => 'What makes The Drive Clinic different from a standard car wash?',
                'answer' => 'We operate under a diagnostic healthcare model. Every vehicle receives a pre-wash visual assessment, is washed with clean microfiber mitts using the 2-bucket grit-guarded method, pH-neutral shampoos, and dried with filtered warm air. We never use coarse cloths or recycled muddy water, preventing swirl marks and scratches.',
                'show_on_home' => true,
                'sort_order' => 1,
            ],
            [
                'category' => 'health_check',
                'question' => 'What is the Digital Car Health Check and is it really free?',
                'answer' => 'Yes! The 5-point cosmetic health check is 100% free with every service or walk-in. Our technicians inspect Exterior/Paint, Interior, Wheels & Tyres, Glass, and Protection status, calculating a 0–100 Drive Health Score sent directly to your phone via WhatsApp.',
                'show_on_home' => true,
                'sort_order' => 2,
            ],
            [
                'category' => 'general',
                'question' => 'Do I need to book in advance or can I walk in?',
                'answer' => 'Walk-ins are always welcome! However, booking online or on WhatsApp guarantees immediate bay access and zero waiting time during peak morning and weekend hours.',
                'show_on_home' => true,
                'sort_order' => 3,
            ],
            [
                'category' => 'protection',
                'question' => 'How does 9H Graphene Ceramic Coating protect against Jammu weather?',
                'answer' => 'Jammu experiences extreme summer heat, UV radiation, and heavy seasonal monsoon downpours. Graphene ceramic creates a permanent covalent barrier that resists UV paint fading, bird dropping etching, chemical contaminants, and makes cleaning effortless with intense water beading.',
                'show_on_home' => true,
                'sort_order' => 4,
            ],
            [
                'category' => 'membership',
                'question' => 'How does Drive Club membership work and can I transfer it?',
                'answer' => 'Drive Club is an annual membership offering fixed wash counts and substantial discounts across detailing and interior sanitization. Memberships are tied to your vehicle registration number and can be transferred if you sell and replace your vehicle.',
                'show_on_home' => true,
                'sort_order' => 5,
            ],
            [
                'category' => 'wash',
                'question' => 'How long does an Essential Foam Wash take?',
                'answer' => 'Our streamlined bay process takes approximately 25 to 30 minutes, allowing you to relax in our customer lounge while your car is serviced.',
                'show_on_home' => false,
                'sort_order' => 6,
            ],
            [
                'category' => 'detailing',
                'question' => 'Will machine paint correction thin my car\'s clear coat?',
                'answer' => 'We measure clear coat depth with digital micron gauges prior to any polishing. Our compounding techniques remove only 2–3 microns of clear coat, safely preserving 95%+ of your factory paint thickness.',
                'show_on_home' => false,
                'sort_order' => 7,
            ],
            [
                'category' => 'interior',
                'question' => 'Is chemical smell left behind after interior deep clean?',
                'answer' => 'No. We use eco-friendly citrus and enzymatic sanitizers, followed by dry steam extraction and natural air neutralizers, leaving a subtle fresh clean scent without harsh chemical fumes.',
                'show_on_home' => false,
                'sort_order' => 8,
            ],
            [
                'category' => 'pricing',
                'question' => 'What payment methods do you accept at the studio?',
                'answer' => 'We accept all major UPI apps (Google Pay, PhonePe, Paytm), credit/debit cards, and cash. You receive a digital GST invoice on WhatsApp immediately upon payment.',
                'show_on_home' => false,
                'sort_order' => 9,
            ],
            [
                'category' => 'health_check',
                'question' => 'What is the mandatory disclaimer on the Drive Health Score?',
                'answer' => 'The Drive Health Score describes cosmetic condition only (paint, interior, glass, wheels, surface protection). It is not a certified mechanical, roadworthiness or safety inspection.',
                'show_on_home' => false,
                'sort_order' => 10,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(['question' => $faq['question']], $faq);
        }
    }
}
