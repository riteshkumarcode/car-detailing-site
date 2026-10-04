<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::first();
        $washCat = ServiceCategory::where('slug', 'wash')->first();
        $detailingCat = ServiceCategory::where('slug', 'detailing')->first();
        $protectionCat = ServiceCategory::where('slug', 'protection')->first();
        $interiorCat = ServiceCategory::where('slug', 'interior')->first();

        $services = [
            [
                'branch_id' => $branch?->id,
                'service_category_id' => $washCat?->id,
                'name' => 'Essential Diagnostic Foam Wash',
                'slug' => 'essential-foam-wash',
                'short_description' => 'Systematic 2-bucket wash with snow foam pre-soak, wheel decontamination and interior vacuuming.',
                'full_description' => 'Our signature entry-level treatment engineered to clean your vehicle without introducing swirl marks. We begin with a touchless high-lubricity foam pre-wash to lift road grime, followed by a 2-bucket grit-guarded microfiber wash, iron-decontaminating wheel clean, and finished with streak-free glass and express interior vacuum.',
                'target_problem' => 'Accumulated road film, brake dust, and surface dirt that damage clear coat when washed improperly.',
                'who_its_for' => 'Daily drivers and enthusiasts who want a safe, swirl-free regular maintenance wash.',
                'duration_minutes' => 25,
                'price_hatchback' => 499.00,
                'price_sedan' => 649.00,
                'price_suv' => 799.00,
                'price_other' => 649.00,
                'is_price_on_inspection' => false,
                'whats_included' => [
                    '5-point cosmetic check and condition logging',
                    'pH-neutral snow foam touchless pre-soak',
                    '2-bucket method with grit guards & plush microfiber mitts',
                    'Deep wheel face and tyre wall decontamination',
                    'High-pressure underbody rinse',
                    'Microfiber drying with filtered warm air blowout',
                    'Express interior floor & seat vacuuming',
                    'Streak-free glass clean inside and out',
                    'Tyre dressing with satin UV barrier',
                ],
                'process_steps' => [
                    ['step_number' => 1, 'title' => 'Diagnostic Assessment', 'description' => 'Visual scan of paint condition, brake dust levels, and contaminated zones.'],
                    ['step_number' => 2, 'title' => 'Snow Foam Pre-Soak', 'description' => 'Lifting coarse traffic film safely before any contact is made.'],
                    ['step_number' => 3, 'title' => '2-Bucket Contact Wash', 'description' => 'Gentle top-to-bottom wash with separated mitts for lower dirty panels.'],
                    ['step_number' => 4, 'title' => 'Touchless Blow Drying', 'description' => 'Filtered air purging trapped water from mirrors, badges, and crevices.'],
                ],
                'faqs' => [
                    ['question' => 'How long does the Essential Foam Wash take?', 'answer' => 'Typically about 25 to 30 minutes in our dedicated wash bay.'],
                    ['question' => 'Do you use dirty rags or recycled muddy water?', 'answer' => 'Never. We use fresh water, pH-balanced premium shampoos, and clean sanitized microfiber mitts for every car.'],
                ],
                'add_ons' => [
                    ['name' => 'Hydrophobic Spray Sealant Boost', 'price' => 299, 'duration_minutes' => 10],
                    ['name' => 'Windshield Rain Repellent Treatment', 'price' => 399, 'duration_minutes' => 15],
                ],
                'sort_order' => 1,
                'is_active' => true,
                'show_on_home' => true,
                'meta_title' => 'Essential Foam Car Wash in Nanak Nagar, Jammu | The Drive Clinic',
                'meta_description' => 'Professional 25-min diagnostic foam car wash in Nanak Nagar, Jammu. 2-bucket safe wash starting from ₹499.',
            ],
            [
                'branch_id' => $branch?->id,
                'service_category_id' => $interiorCat?->id,
                'name' => 'Interior Deep Sanitization & Rejuvenation',
                'slug' => 'interior-deep-clean',
                'short_description' => 'Complete cabin restoration with antimicrobial steam cleaning, hot-water carpet extraction and leather conditioning.',
                'full_description' => 'A clinical-grade deep clean that targets ingrained dirt, stubborn stains, allergens, and bacteria. We use 140°C pressurized dry steam to sterilize AC vents, deep-extract upholstered seats and carpets with enzymatic cleaners, and nourish leather and trim with matte UV-protectant balms.',
                'target_problem' => 'Food spills, pet hair, sticky residues, bacterial growth in AC vents, and unpleasant cabin odours.',
                'who_its_for' => 'Cars with stained fabric, family vehicles needing sanitization, and pre-owned vehicle buyers.',
                'duration_minutes' => 90,
                'price_hatchback' => 1499.00,
                'price_sedan' => 1999.00,
                'price_suv' => 2499.00,
                'price_other' => 1999.00,
                'is_price_on_inspection' => false,
                'whats_included' => [
                    'Complete deep vacuuming of seats, floor, boot & crevices',
                    'Hot-water injection extraction for fabric seats and carpets',
                    '140°C pressurized dry steam sanitization of AC vents & ducting',
                    'Leather seat scrub and pH-balanced conditioning balm',
                    'Roof liner spot decontamination with dry micro-foam',
                    'Dashboard, door cards, and console deep degreasing',
                    'Matte non-greasy UV anti-static dressing on all plastic trims',
                    'Ozone odour neutralization cycle',
                ],
                'process_steps' => [
                    ['step_number' => 1, 'title' => 'Dry Debris Extraction', 'description' => 'High-power dual motor vacuuming with detailing brushes.'],
                    ['step_number' => 2, 'title' => 'Steam & Chemical Scrub', 'description' => 'Agitating stains on fabric and leather with dedicated horsehair brushes.'],
                    ['step_number' => 3, 'title' => 'Hot-Water Extraction', 'description' => 'Extracting deeply embedded grime and liquid residue from upholstery.'],
                    ['step_number' => 4, 'title' => 'Antimicrobial Dressing', 'description' => 'Conditioning and sealing surfaces against UV degradation and cracking.'],
                ],
                'faqs' => [
                    ['question' => 'Will my seats be wet after the interior deep clean?', 'answer' => 'Our extraction equipment pulls 90% of moisture out immediately. Seats dry completely within 1 to 2 hours in a ventilated area.'],
                    ['question' => 'Does this remove stubborn smells like smoke or dampness?', 'answer' => 'Yes, our combination of steam sterilization, hot extraction and ozone air treatment neutralizes odours at the root.'],
                ],
                'add_ons' => [
                    ['name' => 'Fabric Hydrophobic Guard Coating', 'price' => 799, 'duration_minutes' => 20],
                    ['name' => 'Engine Bay Steam Degrease', 'price' => 599, 'duration_minutes' => 25],
                ],
                'sort_order' => 2,
                'is_active' => true,
                'show_on_home' => true,
                'meta_title' => 'Interior Car Cleaning & Sanitization Jammu | The Drive Clinic',
                'meta_description' => 'Hospital-grade steam interior detailing, seat shampooing and AC sanitization in Jammu from ₹1,499.',
            ],
            [
                'branch_id' => $branch?->id,
                'service_category_id' => $detailingCat?->id,
                'name' => 'Multi-Stage Paint Correction & Gloss Restoration',
                'slug' => 'paint-correction-gloss',
                'short_description' => 'Precision rotary and dual-action machine polishing removing 80–90% of swirl marks, light scratches and haze.',
                'full_description' => 'True detailing is paint restoration. Using digital paint depth gauges, compound abrasives, and optical polishing pads, we systematically level clear coat imperfections, scratches, acid rain etching, and micro-marring to restore mirror-like clarity and maximum gloss.',
                'target_problem' => 'Spiderweb swirls, dull oxidized paint, wash scratches, and buffer trails from untrained cleaners.',
                'who_its_for' => 'Vehicles with faded or scratched clear coats seeking showroom-level reflection.',
                'duration_minutes' => 180,
                'price_hatchback' => 3999.00,
                'price_sedan' => 4999.00,
                'price_suv' => 5999.00,
                'price_other' => 4999.00,
                'is_price_on_inspection' => false,
                'whats_included' => [
                    'Multi-stage clay bar & iron fallout decontamination',
                    'Electronic paint depth measurement and thickness mapping',
                    'Stage 1: Heavy cutting compound to level swirls and scratches',
                    'Stage 2: Micro-finishing jewel polish for pure gloss clarity',
                    'Plastics and rubber trim taping protection',
                    'IPA wipe-down to reveal true defect-free finish',
                    '12-month synthetic polymer sealant application',
                ],
                'process_steps' => [
                    ['step_number' => 1, 'title' => 'Chemical & Mechanical Decontamination', 'description' => 'Dissolving iron particles and claying out embedded contaminants.'],
                    ['step_number' => 2, 'title' => 'Micron Depth Measurement', 'description' => 'Assessing clear coat thickness across all metal and composite panels.'],
                    ['step_number' => 3, 'title' => 'Precision Machine Compounding', 'description' => 'Eliminating 80%+ of surface defects with dual-action polishers.'],
                    ['step_number' => 4, 'title' => 'Gloss Jewelling & Sealing', 'description' => 'Refining to optical clarity and locking in gloss with durable sealant.'],
                ],
                'faqs' => [
                    ['question' => 'Will this remove deep key scratches?', 'answer' => 'Scratches that have penetrated through the clear coat into the primer cannot be safely buffed out without repainting, but our technicians will round down the edges to make them significantly less visible.'],
                ],
                'add_ons' => [
                    ['name' => 'Headlight UV Restoration & Polish', 'price' => 799, 'duration_minutes' => 30],
                    ['name' => 'Ceramic Glass Coating', 'price' => 1499, 'duration_minutes' => 45],
                ],
                'sort_order' => 3,
                'is_active' => true,
                'show_on_home' => true,
                'meta_title' => 'Car Detailing & Paint Correction in Jammu | The Drive Clinic',
                'meta_description' => 'Multi-stage machine paint correction in Jammu. Remove swirls, scratches and restore deep mirror gloss from ₹3,999.',
            ],
            [
                'branch_id' => $branch?->id,
                'service_category_id' => $protectionCat?->id,
                'name' => '9H Graphene Ceramic Coating Shield',
                'slug' => 'ceramic-coating-9h',
                'short_description' => 'Multi-year nano-graphene ceramic protection providing extreme chemical resistance, scratch defense and self-cleaning gloss.',
                'full_description' => 'The pinnacle of automotive paint protection. Graphene-infused ceramic forms a permanent crystalline covalent bond with your vehicle clear coat. Rated at 9H pencil hardness, it provides unmatched hydrophobic water-beading, UV shielding against Jammu sun, anti-corrosion defense, and deep candy gloss for up to 3 years.',
                'target_problem' => 'Environmental etching, bird dropping stains, UV oxidation, fading paint, and difficult cleaning.',
                'who_its_for' => 'New vehicles and corrected cars seeking long-term preservation of paint value and ease of maintenance.',
                'duration_minutes' => 360,
                'price_hatchback' => 14999.00,
                'price_sedan' => 18999.00,
                'price_suv' => 22999.00,
                'price_other' => 18999.00,
                'is_price_on_inspection' => false,
                'whats_included' => [
                    'Full multi-stage paint correction included prior to coating',
                    'Dual-layer 9H Graphene Ceramic Coating application',
                    'Coating of all painted bodywork, lights, and exterior plastics',
                    'Alloy wheel face ceramic protection',
                    'Infrared heat-lamp curing in climate-controlled booth',
                    'Digital Car Passport warranty certification logged',
                    'Complimentary first maintenance inspection and wash',
                ],
                'process_steps' => [
                    ['step_number' => 1, 'title' => 'Full Paint Prep & Correction', 'description' => 'Perfecting the clear coat to 90%+ defect-free state before locking it in.'],
                    ['step_number' => 2, 'title' => 'Alcohol Panel Wipe', 'description' => 'Removing all polishing oils for optimal ceramic molecular bonding.'],
                    ['step_number' => 3, 'title' => 'Dual-Layer Ceramic Application', 'description' => 'Cross-hatch precision application of base coat and graphene top coat.'],
                    ['step_number' => 4, 'title' => 'Infrared Curing & Inspection', 'description' => 'Thermal curing ensuring maximum cross-link density and hardness.'],
                ],
                'faqs' => [
                    ['question' => 'How long does the ceramic coating last?', 'answer' => 'Our 9H Graphene Ceramic Coating provides 3 to 5 years of verified protection when maintained with recommended washes.'],
                    ['question' => 'Does this prevent rock chips?', 'answer' => 'Ceramic coating protects against chemical stains, swirls, oxidation and light scratches. For high-speed gravel protection, Paint Protection Film (PPF) is recommended.'],
                ],
                'add_ons' => [
                    ['name' => 'Windshield & Glass Ceramic Hydrophobic Coat', 'price' => 1999, 'duration_minutes' => 45],
                    ['name' => 'Leather Seat Ceramic Stain Barrier', 'price' => 2499, 'duration_minutes' => 60],
                ],
                'sort_order' => 4,
                'is_active' => true,
                'show_on_home' => true,
                'meta_title' => 'Ceramic Coating in Jammu | 9H Graphene Shield | The Drive Clinic',
                'meta_description' => 'Certified 9H Graphene Ceramic Coating studio in Nanak Nagar, Jammu. 3+ year warranty and mirror gloss from ₹14,999.',
            ],
            [
                'branch_id' => $branch?->id,
                'service_category_id' => $washCat?->id,
                'name' => 'Underbody Anti-Rust & Corrosion Coating',
                'slug' => 'underbody-anti-rust',
                'short_description' => 'Thick rubberized bitumen and wax coating protecting chassis, wheel wells and suspension components from rust.',
                'full_description' => 'Jammu terrain and seasonal rains expose chassis components to road grime, stones, and corrosive moisture. We pressure-wash, degrease, and apply a heavy-duty rubberized underbody compound that seals metal seams, dampens road noise, and prevents chassis rust.',
                'target_problem' => 'Underbody chassis corrosion, stone-chip chipping, and road noise transmission.',
                'who_its_for' => 'SUVs, highway commuters, and vehicles operated in high-humidity or hill terrains.',
                'duration_minutes' => 60,
                'price_hatchback' => 1999.00,
                'price_sedan' => 2499.00,
                'price_suv' => 2999.00,
                'price_other' => 2499.00,
                'is_price_on_inspection' => false,
                'whats_included' => [
                    'Underbody hydraulic lift inspection and degreasing',
                    'High-pressure steam clean of wheel wells & chassis rails',
                    'Masking of exhaust system, brake discs and drive shafts',
                    'Even high-pressure spray of rubberized anti-corrosion barrier',
                    'Cavity wax injection into frame rails and sills',
                ],
                'process_steps' => [
                    ['step_number' => 1, 'title' => 'Underbody Decontamination', 'description' => 'Pressure washing mud and road salts from all underside components.'],
                    ['step_number' => 2, 'title' => 'Masking Critical Parts', 'description' => 'Safeguarding moving joints, brake lines, and exhaust heat shields.'],
                    ['step_number' => 3, 'title' => 'Airless Spray Application', 'description' => 'Applying uniform anti-rust barrier across floor pan and wheel arches.'],
                ],
                'faqs' => [
                    ['question' => 'How often should underbody coating be applied?', 'answer' => 'Typically once every 2 to 3 years provides comprehensive rust prevention.'],
                ],
                'add_ons' => [
                    ['name' => 'Silencer Heat-Resistant Zinc Coating', 'price' => 699, 'duration_minutes' => 20],
                ],
                'sort_order' => 5,
                'is_active' => true,
                'show_on_home' => true,
                'meta_title' => 'Underbody Anti-Rust Coating Jammu | The Drive Clinic',
                'meta_description' => 'Heavy-duty rubberized underbody chassis rust protection and cavity wax sealing in Jammu from ₹1,999.',
            ],
            [
                'branch_id' => $branch?->id,
                'service_category_id' => $interiorCat?->id,
                'name' => 'Engine Bay Decontamination & Dressing',
                'slug' => 'engine-bay-decontamination',
                'short_description' => 'Safe low-pressure steam degreasing of engine bay plastics, hoses, and metal with anti-static dressing.',
                'full_description' => 'Keep your powertrain clean and easy to inspect. We safely mask delicate electronic sensors and alternator, dissolve built-up oil film and road grime with dry steam, and condition all hoses and plastic covers with a dry-touch heat-resistant protective dressing.',
                'target_problem' => 'Grease buildup, dust accumulation, dried mud, and faded engine cover plastics.',
                'who_its_for' => 'Car owners maintaining resale value and preparing for routine service inspections.',
                'duration_minutes' => 45,
                'price_hatchback' => 799.00,
                'price_sedan' => 999.00,
                'price_suv' => 1199.00,
                'price_other' => 999.00,
                'is_price_on_inspection' => false,
                'whats_included' => [
                    'Sensitive electrical components masking',
                    'Biodegradable citrus degreaser application',
                    'Detail brush agitation in tight crevices',
                    'Low-moisture steam rinse',
                    'Complete warm air blowout',
                    'Heat-resistant OEM satin dressing',
                ],
                'process_steps' => [
                    ['step_number' => 1, 'title' => 'Sensor & Battery Masking', 'description' => 'Protecting alternator, ECU, and fuse boxes with waterproof covers.'],
                    ['step_number' => 2, 'title' => 'Citrus Steam Degrease', 'description' => 'Dissolving engine grease safely with low-moisture steam.'],
                    ['step_number' => 3, 'title' => 'Drying & Dressing', 'description' => 'Purging water with compressed air and applying satin UV protectant.'],
                ],
                'faqs' => [
                    ['question' => 'Is engine washing safe for modern electronic cars?', 'answer' => 'Yes, because we never use flood pressure water. We use targeted dry steam and meticulously mask all electrical modules.'],
                ],
                'add_ons' => [],
                'sort_order' => 6,
                'is_active' => true,
                'show_on_home' => true,
                'meta_title' => 'Engine Bay Cleaning & Detailing Jammu | The Drive Clinic',
                'meta_description' => 'Safe steam engine bay degreasing and detailing in Nanak Nagar, Jammu from ₹799.',
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['slug' => $service['slug']], $service);
        }
    }
}
