<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@northpoblacion.gov.ph')->first();

        Announcement::firstOrCreate(
            ['title' => 'Barangay General Assembly & Town Hall Meeting'],
            [
                'description' => 'All residents of Barangay North Poblacion are cordially invited to attend our semi-annual Barangay General Assembly. Topics include the presentation of financial reports, development projects, and public open forum.',
                'category' => 'Community Meeting',
                'publication_status' => 'published',
                'event_start' => now()->addDays(5)->setHour(9)->setMinute(0),
                'event_end' => now()->addDays(5)->setHour(12)->setMinute(0),
                'event_location' => 'North Poblacion Municipal Gymnasium',
                'created_by' => $admin?->id,
                'published_at' => now()->subDays(2),
            ]
        );

        Announcement::firstOrCreate(
            ['title' => 'Scheduled Community Clean-Up Drive (Operation Kalimpyo)'],
            [
                'description' => 'Join your Purok leaders this coming Saturday for our synchronized clean-up drive. Let us clear waterways and prevent dengue breeding grounds.',
                'category' => 'Sanitation & Health',
                'publication_status' => 'published',
                'event_start' => now()->addDays(8)->setHour(6)->setMinute(0),
                'event_end' => now()->addDays(8)->setHour(10)->setMinute(0),
                'event_location' => 'All Purok Centers, North Poblacion',
                'created_by' => $admin?->id,
                'published_at' => now()->subDay(),
            ]
        );

        Announcement::firstOrCreate(
            ['title' => 'Free Anti-Rabies Vaccination for Dogs and Cats'],
            [
                'description' => 'In cooperation with the Municipal Agriculture Office, free anti-rabies vaccines will be administered to all household pets.',
                'category' => 'Veterinary Services',
                'publication_status' => 'published',
                'event_start' => now()->addDays(12)->setHour(8)->setMinute(30),
                'event_end' => now()->addDays(12)->setHour(16)->setMinute(0),
                'event_location' => 'Barangay Hall Grounds',
                'created_by' => $admin?->id,
                'published_at' => now()->subDays(3),
            ]
        );
    }
}
