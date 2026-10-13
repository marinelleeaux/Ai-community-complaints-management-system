<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\Purok;
use App\Models\User;
use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    public function run(): void
    {
        $jan = User::where('email', 'jan.auditor@gmail.com')->first();
        if (! $jan) {
            return;
        }

        $wasteCat = ComplaintCategory::where('name', 'Solid Waste & Sanitation')->first();
        $roadCat = ComplaintCategory::where('name', 'Roads & Infrastructure')->first();
        $waterCat = ComplaintCategory::where('name', 'Water Supply & Drainage')->first();
        $purok5 = Purok::where('name', 'Purok 5')->first();

        // Complaint 1: In Progress (July 1, 2026)
        Complaint::firstOrCreate(
            ['reference_number' => 'NPC-2026-0001'],
            [
                'resident_id' => $jan->id,
                'title' => 'Daghan kaayo og basura sa atubangan sa palengke.',
                'original_description' => 'Daghan kaayo og nagtipun-og nga basura sa atubangan sa merkado publiko. Baho na kaayo unya nag-awas na sa dalan. Palihog og pickup sa garbage truck.',
                'original_language' => 'ceb',
                'english_translation' => 'There is a huge accumulation of garbage in front of the public market. It smells very bad and is overflowing onto the street. Please have the garbage truck collect it.',
                'english_summary' => 'Severe garbage accumulation and overflow in front of the public market causing foul odor and street obstruction.',
                'category_id' => $wasteCat?->id,
                'incident_location' => 'Public Market Entrance, Purok 5',
                'purok_id' => $purok5?->id,
                'incident_date' => '2026-07-01',
                'incident_time' => '07:30:00',
                'ai_suggested_category' => 'Solid Waste & Sanitation',
                'ai_suggested_priority' => 'medium',
                'final_priority' => 'medium',
                'priority_explanation' => 'Uncollected waste in a commercial area with high pedestrian traffic creates sanitation hazards.',
                'status' => 'in_progress',
                'resident_visible_remarks' => 'Barangay Sanitation crew deployed with dump truck for collection.',
                'ai_analysis_status' => 'completed',
                'created_at' => '2026-07-01 08:00:00',
                'updated_at' => '2026-07-01 10:15:00',
            ]
        );

        // Complaint 2: Resolved (July 3, 2026)
        Complaint::firstOrCreate(
            ['reference_number' => 'NPC-2026-0002'],
            [
                'resident_id' => $jan->id,
                'title' => 'Sirado ang dalan tungod sa naguba nga poste.',
                'original_description' => 'Ang dalan pasulod sa Purok 5 sirado kay dunay natumba nga poste sa kuryente human sa kusog nga hangin gahapon sa hapon. Delikado sa mga motorista.',
                'original_language' => 'ceb',
                'english_translation' => 'The road entering Purok 5 is closed due to a fallen electric post after strong winds yesterday afternoon. Dangerous for motorists.',
                'english_summary' => 'Fallen utility pole blocking road access at Purok 5 entrance presenting immediate electrical and traffic hazard.',
                'category_id' => $roadCat?->id,
                'incident_location' => 'Main Access Road, Purok 5',
                'purok_id' => $purok5?->id,
                'incident_date' => '2026-07-03',
                'incident_time' => '14:15:00',
                'ai_suggested_category' => 'Roads & Infrastructure',
                'ai_suggested_priority' => 'high',
                'final_priority' => 'high',
                'priority_explanation' => 'Fallen electrical pole blocking public thoroughfare creates acute risk of electrocution and traffic blockage.',
                'status' => 'resolved',
                'resident_visible_remarks' => 'Coordinated with FIBECO / Electric coop. The pole has been cleared and power restored safely.',
                'ai_analysis_status' => 'completed',
                'resolved_at' => '2026-07-04 16:30:00',
                'created_at' => '2026-07-03 14:30:00',
                'updated_at' => '2026-07-04 16:30:00',
            ]
        );

        // Complaint 3: Pending/Submitted (July 5, 2026)
        Complaint::firstOrCreate(
            ['reference_number' => 'NPC-2026-0003'],
            [
                'resident_id' => $jan->id,
                'title' => 'Naghulog ang tubo sa tubig sa Purok 5.',
                'original_description' => 'Naghulog ug nabali ang main water pipe linya sa tubig sa Purok 5. Kusog ang tagas sa tubig ug nihinay ang agas sa mga kabalayan.',
                'original_language' => 'ceb',
                'english_translation' => 'The main water line pipe in Purok 5 fell down and broke. Strong water leak and water pressure in households has dropped.',
                'english_summary' => 'Main waterline breakage in Purok 5 causing significant water leakage and low household pressure.',
                'category_id' => $waterCat?->id,
                'incident_location' => 'Near Purok 5 Chapel',
                'purok_id' => $purok5?->id,
                'incident_date' => '2026-07-05',
                'incident_time' => '09:00:00',
                'ai_suggested_category' => 'Water Supply & Drainage',
                'ai_suggested_priority' => 'high',
                'final_priority' => 'high',
                'priority_explanation' => 'Main waterline breakage deprives residents of potable water and causes infrastructure water wastage.',
                'status' => 'submitted',
                'resident_visible_remarks' => 'Endorsed to Barangay Maintenance team for inspection.',
                'ai_analysis_status' => 'completed',
                'created_at' => '2026-07-05 09:15:00',
                'updated_at' => '2026-07-05 09:15:00',
            ]
        );
    }
}
