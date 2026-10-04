<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Owner Admin',
                'email' => 'owner@thedriveclinic.in',
                'phone' => '9419100001',
                'role' => 'owner',
                'branch_id' => 1,
                'password' => 'password',
                'is_active' => true,
            ],
            [
                'name' => 'Studio Manager',
                'email' => 'manager@thedriveclinic.in',
                'phone' => '9419100002',
                'role' => 'manager',
                'branch_id' => 1,
                'password' => 'password',
                'is_active' => true,
            ],
            [
                'name' => 'Detailer Staff',
                'email' => 'staff@thedriveclinic.in',
                'phone' => '9419100003',
                'role' => 'staff',
                'branch_id' => 1,
                'password' => 'password',
                'is_active' => true,
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            $user->syncRoles([$userData['role']]);
        }
    }
}
