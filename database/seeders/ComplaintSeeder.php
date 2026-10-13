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
        $purok5 = Purok::where('name', 'Purok 5')->first() ?? Purok::first();

        // Complaint 1: In Progress (Solid Waste) - Medium Priority
        Complaint::updateOrCreate(
            ['reference_number' => 'NPC-2026-0001'],
            [
                'resident_id' => $jan->id,
                'title' => 'Severe Garbage Accumulation and Road Overflow',
                'original_description' => 'Daghan kaayo og nagtipun-og nga basura sa atubangan sa merkado publiko sa Purok 5. Baho na kaayo unya nag-awas na sa dalan. Palihog og hakot dayon sa garbage truck.',
                'original_language' => 'ceb',
                'english_translation' => 'There is a severe pile of uncollected garbage in front of the public market in Purok 5. It is foul-smelling and overflowing onto the street. Please deploy the garbage truck immediately.',
                'english_summary' => 'Severe garbage accumulation and overflow in front of the public market causing foul odor and street obstruction.',
                'category_id' => $wasteCat?->id,
                'incident_location' => 'Public Market Entrance, Purok 5',
                'purok_id' => $purok5?->id,
                'incident_date' => '2026-07-01',
                'incident_time' => '07:30:00',
                'ai_suggested_category' => 'Solid Waste & Sanitation',
                'ai_suggested_priority' => 'medium',
                'final_priority' => 'medium',
                'priority_explanation' => 'Uncollected commercial waste creates environmental health hazards and street obstruction.',
                'status' => 'in_progress',
                'resident_visible_remarks' => 'Barangay Sanitation crew deployed with dump truck for collection.',
                'ai_analysis_status' => 'completed',
                'created_at' => '2026-07-01 08:00:00',
                'updated_at' => '2026-07-01 10:15:00',
            ]
        );

        // Complaint 2: Resolved (Roads & Electrical) - URGENT Priority
        Complaint::updateOrCreate(
            ['reference_number' => 'NPC-2026-0002'],
            [
                'resident_id' => $jan->id,
                'title' => 'Fallen Electric Utility Post Blocking Roadway',
                'original_description' => 'Ang dalan pasulod sa Purok 5 sirado kay dunay natumba nga poste sa kuryente human sa kusog nga hangin gahapon. Delikado kaayo sa mga motorista kay dunay live wire.',
                'original_language' => 'ceb',
                'english_translation' => 'The road entering Purok 5 is impassable because an electric utility pole fell after strong winds yesterday. Extremely hazardous to motorists due to exposed live wires.',
                'english_summary' => 'Fallen electrical utility pole blocking road access with exposed live wires presenting immediate electrocution hazard.',
                'category_id' => $roadCat?->id,
                'incident_location' => 'Main Access Road, Purok 5',
                'purok_id' => $purok5?->id,
                'incident_date' => '2026-07-03',
                'incident_time' => '14:15:00',
                'ai_suggested_category' => 'Roads & Infrastructure',
                'ai_suggested_priority' => 'urgent',
                'final_priority' => 'urgent',
                'priority_explanation' => 'Fallen high-voltage utility pole blocking public thoroughfare creates acute risk of electrocution and complete vehicular blockage.',
                'status' => 'resolved',
                'resident_visible_remarks' => 'Coordinated with FIBECO / Electric coop. The pole and wires have been cleared and power restored safely.',
                'ai_analysis_status' => 'completed',
                'resolved_at' => '2026-07-04 16:30:00',
                'created_at' => '2026-07-03 14:30:00',
                'updated_at' => '2026-07-04 16:30:00',
            ]
        );

        // Complaint 3: Submitted (Water Supply) - High Priority
        Complaint::updateOrCreate(
            ['reference_number' => 'NPC-2026-0003'],
            [
                'resident_id' => $jan->id,
                'title' => 'Burst Municipal Waterline Pipe and Street Flooding',
                'original_description' => 'Naghulog ug nabali ang main water pipe linya sa tubig sa Purok 5. Kusog kaayo ang tagas sa tubig nga nagbaha na sa kanal ug nihinay ang agas sa mga kabalayan.',
                'original_language' => 'ceb',
                'english_translation' => 'The main municipal water pipe in Purok 5 broke and collapsed. Substantial water leakage is flooding the roadside drainage and household water pressure has dropped severely.',
                'english_summary' => 'Main municipal waterline pipe rupture causing severe water loss, drainage overflow, and low household pressure.',
                'category_id' => $waterCat?->id,
                'incident_location' => 'Near Purok 5 Chapel',
                'purok_id' => $purok5?->id,
                'incident_date' => '2026-07-05',
                'incident_time' => '09:00:00',
                'ai_suggested_category' => 'Water Supply & Drainage',
                'ai_suggested_priority' => 'high',
                'final_priority' => 'high',
                'priority_explanation' => 'Main waterline breakage deprives residential households of potable water and causes infrastructure water wastage.',
                'status' => 'submitted',
                'resident_visible_remarks' => 'Endorsed to Barangay Maintenance team for emergency pipe replacement.',
                'ai_analysis_status' => 'completed',
                'created_at' => '2026-07-05 09:15:00',
                'updated_at' => '2026-07-05 09:15:00',
            ]
        );
    }
}
