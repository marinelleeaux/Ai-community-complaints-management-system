<?php

namespace Database\Seeders;

use App\Models\Purok;
use Illuminate\Database\Seeder;

class PurokSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 12; $i++) {
            Purok::firstOrCreate(
                ['name' => "Purok {$i}"],
                ['is_active' => true]
            );
        }
    }
}
