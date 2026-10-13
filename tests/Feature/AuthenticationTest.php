<?php

namespace Tests\Feature;

use App\Models\Purok;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Purok::create(['name' => 'Purok 1', 'is_active' => true]);
        Purok::create(['name' => 'Purok 2', 'is_active' => true]);
    }

    public function test_guest_is_redirected_to_login_from_home(): void
    {
        $response = $this->get('/');
        $response->assertRedirect(route('login'));
    }

    public function test_guest_can_view_login_page(): void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee('Barangay North Poblacion');
    }

    public function test_guest_can_view_registration_page_with_puroks(): void
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
        $response->assertSee('Purok 1');
        $response->assertSee('Purok 2');
    }

    public function test_resident_can_register_without_email_verification_and_starts_as_pending(): void
    {
        $purok = Purok::first();

        $response = $this->post(route('register'), [
            'name' => 'Juan Dela Cruz',
            'email' => 'juan.delacruz@example.com',
            'phone' => '09123456789',
            'purok_id' => $purok->id,
            'address' => 'House 123, Purok 1',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'privacy_agreement' => '1',
        ]);

        $response->assertRedirect(route('pending-approval'));

        $this->assertDatabaseHas('users', [
            'email' => 'juan.delacruz@example.com',
            'role' => 'resident',
            'account_status' => 'pending',
        ]);
    }

    public function test_pending_resident_cannot_access_resident_dashboard(): void
    {
        $purok = Purok::first();
        $user = User::create([
            'name' => 'Pending Resident',
            'email' => 'pending@example.com',
            'password' => Hash::make('Password123!'),
            'role' => 'resident',
            'account_status' => 'pending',
            'is_active' => true,
            'purok_id' => $purok->id,
            'address' => 'Sample address',
        ]);

        $response = $this->actingAs($user)->get(route('resident.dashboard'));
        $response->assertRedirect(route('pending-approval'));
    }

    public function test_approved_resident_can_access_resident_dashboard(): void
    {
        $purok = Purok::first();
        $user = User::create([
            'name' => 'Jan Marinelle Auditor',
            'email' => 'jan.auditor@gmail.com',
            'password' => Hash::make('Password123!'),
            'role' => 'resident',
            'account_status' => 'approved',
            'is_active' => true,
            'purok_id' => $purok->id,
            'address' => 'Purok 3, North Poblacion',
        ]);

        $response = $this->actingAs($user)->get(route('resident.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Jan Marinelle Auditor');
        $response->assertSee('North Poblacion, Maramag, Bukidnon');
    }

    public function test_admin_can_access_admin_dashboard_but_not_unauthorized_user(): void
    {
        $purok = Purok::first();
        $resident = User::create([
            'name' => 'Regular Resident',
            'email' => 'regular@example.com',
            'password' => Hash::make('Password123!'),
            'role' => 'resident',
            'account_status' => 'approved',
            'is_active' => true,
            'purok_id' => $purok->id,
            'address' => 'Sample',
        ]);

        // Resident blocked from admin dashboard
        $response = $this->actingAs($resident)->get(route('admin.dashboard'));
        $response->assertRedirect(route('access-denied'));

        // Admin allowed
        $admin = User::create([
            'name' => 'Barangay Captain',
            'email' => 'admin@northpoblacion.gov.ph',
            'password' => Hash::make('Password123!'),
            'role' => 'admin',
            'account_status' => 'approved',
            'is_active' => true,
        ]);

        $adminResponse = $this->actingAs($admin)->get(route('admin.dashboard'));
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('Complaints by Category');
        $adminResponse->assertSee('Pending Residents');
    }

    public function test_user_can_logout(): void
    {
        $admin = User::create([
            'name' => 'Barangay Captain',
            'email' => 'admin@northpoblacion.gov.ph',
            'password' => Hash::make('Password123!'),
            'role' => 'admin',
            'account_status' => 'approved',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('logout'));
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
