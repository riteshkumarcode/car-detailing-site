<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            BranchSeeder::class,
            RoleAndPermissionSeeder::class,
            SettingSeeder::class,
            UserSeeder::class,
            ServiceCategorySeeder::class,
            ServiceSeeder::class,
            MembershipPlanSeeder::class,
            TestimonialSeeder::class,
            FaqSeeder::class,
            GalleryItemSeeder::class,
        ]);
    }
}
