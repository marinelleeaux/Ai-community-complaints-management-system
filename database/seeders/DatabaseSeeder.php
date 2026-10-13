<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PurokSeeder::class,
            ComplaintCategorySeeder::class,
            UserSeeder::class,
            ComplaintSeeder::class,
            AnnouncementSeeder::class,
        ]);
    }
}
