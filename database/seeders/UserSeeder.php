<?php

namespace Database\Seeders;

use App\Models\Purok;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $purok5 = Purok::where('name', 'Purok 5')->first();
        $purok2 = Purok::where('name', 'Purok 2')->first();
        $purok3 = Purok::where('name', 'Purok 3')->first();

        // 1. Administrator
        User::firstOrCreate(
            ['email' => 'admin@northpoblacion.gov.ph'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Barangay Administrator',
                'phone' => '0917-123-4567',
                'address' => 'Barangay Hall, North Poblacion, Maramag, Bukidnon',
                'purok_id' => $purok5?->id,
                'role' => 'admin',
                'account_status' => 'approved',
                'approved_at' => now(),
                'theme_preference' => 'light',
                'password' => Hash::make('Password123!'),
            ]
        );

        // 2. Jan Marinelle Auditor (matching reference screenshot)
        User::firstOrCreate(
            ['email' => 'jan.auditor@gmail.com'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Jan Marinelle Auditor',
                'phone' => '0918-987-6543',
                'address' => 'Purok 5, North Poblacion, Maramag, Bukidnon',
                'purok_id' => $purok5?->id,
                'role' => 'resident',
                'account_status' => 'approved',
                'approved_at' => now()->subMonths(3),
                'theme_preference' => 'light',
                'password' => Hash::make('Password123!'),
            ]
        );

        // 3. Second Approved Resident (for cross-user isolation testing)
        User::firstOrCreate(
            ['email' => 'carlos.dizon@gmail.com'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Carlos M. Dizon',
                'phone' => '0919-555-0123',
                'address' => 'Purok 2, North Poblacion, Maramag, Bukidnon',
                'purok_id' => $purok2?->id,
                'role' => 'resident',
                'account_status' => 'approved',
                'approved_at' => now()->subMonths(1),
                'theme_preference' => 'system',
                'password' => Hash::make('Password123!'),
            ]
        );

        // 4. Pending Resident (for testing admin approval workflow)
        User::firstOrCreate(
            ['email' => 'elena.ramos@gmail.com'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Elena Santos Ramos',
                'phone' => '0920-444-9876',
                'address' => 'Purok 3, North Poblacion, Maramag, Bukidnon',
                'purok_id' => $purok3?->id,
                'role' => 'resident',
                'account_status' => 'pending',
                'theme_preference' => 'light',
                'password' => Hash::make('Password123!'),
            ]
        );
    }
}
