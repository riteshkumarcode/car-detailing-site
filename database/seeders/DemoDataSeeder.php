<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\HealthCheck;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\InvoiceService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds for realistic demo/preview operations.
     * Clearly tagged with 'demo' so it can be removed before launch.
     */
    public function run(): void
    {
        /** @var InvoiceService $invoiceService */
        $invoiceService = app(InvoiceService::class);
        $owner = User::where('email', 'owner@thedriveclinic.in')->first();

        // 1. Customer 1: Vikramaditya Jamwal (2 vehicles)
        $customer1 = Customer::updateOrCreate(
            ['mobile' => '9419166777'],
            [
                'branch_id'       => 1,
                'name'            => 'Vikramaditya Jamwal',
                'email'           => 'vikram.jamwal@example.com',
                'area'            => 'Gandhi Nagar, Jammu',
                'referral_source' => 'Word of mouth',
                'date_joined'     => Carbon::parse('2026-06-10'),
                'tags'            => ['vip', 'repeat', 'drive_club_member', 'demo_data'],
                'notes'           => 'Prefers satin tyre dressing. Very particular about clear coat swirl-free finish.',
            ]
        );

        // Vehicle 1A: BMW M340i
        $vehicle1A = Vehicle::updateOrCreate(
            ['registration_number' => 'JK02ZZ9999'],
            [
                'branch_id'    => 1,
                'customer_id'  => $customer1->id,
                'make'         => 'BMW',
                'model'        => 'M340i xDrive',
                'variant'      => 'Shadowline LCI',
                'vehicle_type' => 'sedan',
                'colour'       => 'Tanzanite Blue Metallic',
                'notes'        => 'Demonstration vehicle with full history.',
            ]
        );

        // Vehicle 1B: Mahindra Thar Roxx
        $vehicle1B = Vehicle::updateOrCreate(
            ['registration_number' => 'JK02CY7788'],
            [
                'branch_id'    => 1,
                'customer_id'  => $customer1->id,
                'make'         => 'Mahindra',
                'model'        => 'Thar Roxx',
                'variant'      => 'AX7L 4x4',
                'vehicle_type' => 'suv',
                'colour'       => 'Stealth Black',
                'notes'        => 'Daily highway and hills commuter.',
            ]
        );

        // 2. Customer 2: Priya Sharma (1 vehicle)
        $customer2 = Customer::updateOrCreate(
            ['mobile' => '9419188999'],
            [
                'branch_id'       => 1,
                'name'            => 'Priya Sharma',
                'email'           => 'priya.sharma@example.com',
                'area'            => 'Channi Himmat, Jammu',
                'referral_source' => 'Instagram',
                'date_joined'     => Carbon::parse('2026-08-01'),
                'tags'            => ['new', 'demo_data'],
                'notes'           => 'Interested in 9H Ceramic coating before monsoon.',
            ]
        );

        // Vehicle 2: Hyundai Creta
        $vehicle2 = Vehicle::updateOrCreate(
            ['registration_number' => 'JK02AB5544'],
            [
                'branch_id'    => 1,
                'customer_id'  => $customer2->id,
                'make'         => 'Hyundai',
                'model'        => 'Creta SX(O)',
                'variant'      => 'Turbo Petrol DCT',
                'vehicle_type' => 'suv',
                'colour'       => 'Ranger Khaki',
            ]
        );

        // 3. Health Check for Vehicle 1A (BMW M340i)
        $foamWash = Service::where('slug', 'essential-foam-wash')->first();
        $paintCorrect = Service::where('slug', 'paint-correction-gloss')->first();
        $ceramic = Service::where('slug', 'ceramic-coating-9h')->first();

        $check1 = HealthCheck::updateOrCreate(
            [
                'vehicle_id'  => $vehicle1A->id,
                'customer_id' => $customer1->id,
            ],
            [
                'branch_id'         => 1,
                'user_id'           => $owner?->id ?? 1,
                'check_date'        => Carbon::parse('2026-08-15 11:30:00'),
                'overall_score'     => 92,
                'protection_type'   => 'ceramic',
                'category_scores'   => ['exterior' => 28, 'interior' => 29, 'wheels' => 14, 'glass' => 14, 'protection' => 10],
                'weights_snapshot'  => ['exterior' => 30, 'interior' => 30, 'wheels' => 15, 'glass' => 15, 'protection' => 10],
                'checklist_data'    => [],
                'recommended_today' => [
                    ['name' => 'Essential Diagnostic Foam Wash', 'price' => 649, 'reason' => 'Routine monthly safe maintenance wash.'],
                ],
                'recommended_later' => [
                    ['name' => 'Ceramic Coating Annual Inspection & Booster', 'price' => 2499, 'reason' => 'Hydrophobic layer top-up recommended in 6 months.'],
                ],
                'technician_notes'  => 'Vehicle in pristine clinical condition. Clear coat gloss index 94 GU. Ceramic covalent bond active with 110° water contact angle.',
            ]
        );

        // 4. Invoices
        // Invoice 1 for BMW M340i (Essential Wash)
        if ($vehicle1A->invoices()->count() === 0) {
            $inv1 = $invoiceService->createInvoice([
                'customer_id'       => $customer1->id,
                'vehicle_id'        => $vehicle1A->id,
                'date'              => Carbon::parse('2026-08-15 12:15:00'),
                'payment_method'    => 'upi',
                'payment_reference' => 'UPI/20260815/9981',
                'staff_notes'       => 'Safe maintenance wash with ceramic booster spray.',
                'is_gst_enabled'    => true,
                'gstin'             => '01AAAAA0000A1Z5',
                'items'             => [
                    ['service_id' => $foamWash?->id, 'item_name' => 'Essential Diagnostic Foam Wash', 'quantity' => 1, 'unit_price' => 649.00],
                ],
            ], $owner);
            $inv1->created_at = Carbon::parse('2026-08-15 12:15:00');
            $inv1->saveQuietly();
        }

        // Invoice 2 for BMW M340i (Paint Correction & Ceramic Shield)
        if ($vehicle1A->invoices()->count() === 1) {
            $inv2 = $invoiceService->createInvoice([
                'customer_id'       => $customer1->id,
                'vehicle_id'        => $vehicle1A->id,
                'date'              => Carbon::parse('2026-09-01 16:30:00'),
                'payment_method'    => 'card',
                'payment_reference' => 'POS/HDFC/4402',
                'discount_type'     => 'fixed',
                'discount_value'    => 1000.00,
                'discount_reason'   => 'Inaugural Studio Launch Privilege Discount',
                'staff_notes'       => 'Applied dual layer 9H Graphene coating after 2-stage paint correction.',
                'is_gst_enabled'    => true,
                'gstin'             => '01AAAAA0000A1Z5',
                'items'             => [
                    ['service_id' => $paintCorrect?->id, 'item_name' => 'Multi-Stage Paint Correction & Gloss Restoration', 'quantity' => 1, 'unit_price' => 4999.00],
                    ['service_id' => $ceramic?->id, 'item_name' => '9H Graphene Ceramic Coating Shield', 'quantity' => 1, 'unit_price' => 18999.00],
                ],
            ], $owner);
            $inv2->created_at = Carbon::parse('2026-09-01 16:30:00');
            $inv2->saveQuietly();
        }

        // 5. Today's Bookings on Job Board
        $today = Carbon::today()->format('Y-m-d');
        Booking::updateOrCreate(
            ['mobile' => '9419166777', 'booking_date' => $today, 'booking_time' => '10:00'],
            [
                'branch_id'           => 1,
                'customer_id'         => $customer1->id,
                'vehicle_id'          => $vehicle1B->id,
                'service_id'          => $foamWash?->id,
                'service_name'        => 'Essential Diagnostic Foam Wash',
                'vehicle_type'        => 'suv',
                'price'               => 799.00,
                'duration_minutes'    => 30,
                'slots_count'         => 1,
                'name'                => 'Vikramaditya Jamwal',
                'registration_number' => 'JK02CY7788',
                'make_model'          => 'Mahindra Thar Roxx',
                'status'              => 'in_service',
                'notes'               => 'Underbody muddy after highway trip. Extra arch rinse requested.',
            ]
        );

        Booking::updateOrCreate(
            ['mobile' => '9419188999', 'booking_date' => $today, 'booking_time' => '11:30'],
            [
                'branch_id'           => 1,
                'customer_id'         => $customer2->id,
                'vehicle_id'          => $vehicle2->id,
                'service_id'          => $foamWash?->id,
                'service_name'        => 'Essential Diagnostic Foam Wash',
                'vehicle_type'        => 'suv',
                'price'               => 799.00,
                'duration_minutes'    => 30,
                'slots_count'         => 1,
                'name'                => 'Priya Sharma',
                'registration_number' => 'JK02AB5544',
                'make_model'          => 'Hyundai Creta',
                'status'              => 'arrived',
                'notes'               => 'First time at studio.',
            ]
        );

        // 6. Drive Club Membership for Customer 1 (Vehicle 1A)
        $premiumPlan = \App\Models\MembershipPlan::where('slug', 'premium')->first();
        if ($premiumPlan && $customer1->memberships()->count() === 0) {
            /** @var \App\Services\MembershipService $membershipService */
            $membershipService = app(\App\Services\MembershipService::class);
            $membership = $membershipService->assignPlan(
                customer: $customer1,
                vehicle: $vehicle1A,
                plan: $premiumPlan,
                paymentMethod: 'upi',
                staff: $owner,
                startsAt: Carbon::parse('2026-06-15 10:00:00'),
                notes: 'Enrolled in Premium Healthcare Plan during studio visit.'
            );

            // Seed 1 redemption for essential wash
            if ($foamWash) {
                $membershipService->redeemService(
                    membership: $membership,
                    serviceId: $foamWash->id,
                    units: 1,
                    staff: $owner,
                    notes: 'Maintenance wash redeemed against annual quota'
                );
            }
        }
    }
}
