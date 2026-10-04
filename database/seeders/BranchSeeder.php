<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        Branch::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Jammu - Nanak Nagar',
                'code' => 'JMU01',
                'address' => 'Nanak Nagar, Jammu',
                'city' => 'Jammu',
                'state' => 'Jammu & Kashmir',
                'pincode' => '180004',
                'phone' => '+91 94191 00000',
                'whatsapp_number' => '+91 94191 00000',
                'email' => 'jammu@thedriveclinic.in',
                'is_active' => true,
            ]
        );
    }
}
