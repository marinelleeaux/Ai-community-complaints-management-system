<?php

namespace Database\Seeders;

use App\Models\ComplaintCategory;
use Illuminate\Database\Seeder;

class ComplaintCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Solid Waste & Sanitation',
                'description' => 'Garbage collection, illegal dumping, street sweeping, uncollected waste (Basura ug Kalimpyo)',
            ],
            [
                'name' => 'Roads & Infrastructure',
                'description' => 'Potholes, blocked roads, damaged drainage covers, footpaths, bridges (Dalan ug Imprastraktura)',
            ],
            [
                'name' => 'Water Supply & Drainage',
                'description' => 'Broken water pipes, low water pressure, clogged canals, flooding concerns (Tubig ug Kanal)',
            ],
            [
                'name' => 'Peace and Order',
                'description' => 'Curfew violations, neighborhood disputes, stray violence, safety concerns (Kalinaw ug Kahusay)',
            ],
            [
                'name' => 'Public Nuisance & Noise',
                'description' => 'Excessive videoke noise, unruly gatherings, barking strays, public disturbances (Kasaba ug Panggugulo)',
            ],
            [
                'name' => 'Street Lighting & Hazards',
                'description' => 'Non-functioning streetlights, fallen electrical wires, hazardous trees or poles (Suga ug Kuryente)',
            ],
            [
                'name' => 'Health & Environmental Hazards',
                'description' => 'Stagnant water, mosquito breeding sites, sanitation violations, foul odors (Panglawas ug Kalikopan)',
            ],
            [
                'name' => 'Animal Control & Strays',
                'description' => 'Stray dogs/cats, aggressive animals, rabies prevention (Mga Hayop ug Binuhi)',
            ],
            [
                'name' => 'General Barangay Inquiry',
                'description' => 'Certificates, barangay clearance inquiries, general community concerns (Uban Pa)',
            ],
        ];

        foreach ($categories as $cat) {
            ComplaintCategory::firstOrCreate(
                ['name' => $cat['name']],
                ['description' => $cat['description'], 'is_active' => true]
            );
        }
    }
}
