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

    public function test_guest_can_view_home_landing_page(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('AI-Community');
    }

    public function test_guest_can_view_about_page(): void
    {
        $response = $this->get(route('about'));
        $response->assertStatus(200);
        $response->assertSee('About Our System');
        $response->assertSee('Our Purpose');
        $response->assertSee('Our Vision');
        $response->assertSee('Our Mission');
        $response->assertSee('Our Values');
        $response->assertSee('A responsive barangay for a better tomorrow');
    }

    public function test_guest_can_view_how_it_works_page(): void
    {
        $response = $this->get(route('how-it-works'));
        $response->assertStatus(200);
        $response->assertSee('HOW IT WORKS');
        $response->assertSee('Simple Steps, Real Solutions');
        $response->assertSee('Submit Your Complaint');
        $response->assertSee('AI Translation');
        $response->assertSee('Barangay Review');
        $response->assertSee('Track Progress');
    }

    public function test_guest_can_view_and_submit_contact_page(): void
    {
        $response = $this->get(route('contact'));
        $response->assertStatus(200);
        $response->assertSee('GET IN TOUCH');
        $response->assertSee('Contact Us');
        $response->assertSee('Send Us a Message');
        $response->assertSee('+63 917 123 4567');
        $response->assertSee('northpoblacion.maramag@gmail.com');

        $postResponse = $this->post(route('contact.send'), [
            'name' => 'Maria Santos',
            'email' => 'maria.santos@gmail.com',
            'subject' => 'Streetlight Inquiry',
            'message' => 'Good day, may we know the schedule of inspection for Purok 3 streetlights?',
        ]);
        $postResponse->assertSessionHas('success');
    }

    public function test_guest_can_view_login_page(): void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee('Barangay North Poblacion');
    }

    public function test_resident_can_login_via_post_and_redirect_to_dashboard(): void
    {
        $purok = Purok::first();
        User::create([
            'name' => 'Jan Marinelle Auditor',
            'email' => 'jan.auditor@gmail.com',
            'password' => Hash::make('Password123!'),
            'role' => 'resident',
            'account_status' => 'approved',
            'is_active' => true,
            'purok_id' => $purok->id,
            'address' => 'Purok 3',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'jan.auditor@gmail.com',
            'password' => 'Password123!',
        ]);

        $response->assertRedirect(route('resident.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_admin_can_login_via_post_and_redirect_to_admin_dashboard(): void
    {
        User::create([
            'name' => 'Barangay Official',
            'email' => 'admin@northpoblacion.gov.ph',
            'password' => Hash::make('Password123!'),
            'role' => 'admin',
            'account_status' => 'approved',
            'is_active' => true,
        ]);

        $response = $this->post(route('login'), [
            'email' => 'admin@northpoblacion.gov.ph',
            'password' => 'Password123!',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
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

        // Resident blocked from admin dashboard (Strict Portal Segregation)
        $response = $this->actingAs($resident)->get(route('admin.dashboard'));
        $response->assertRedirect(route('resident.dashboard'));

        // Admin allowed on admin dashboard
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

        // Admin blocked from resident dashboard (Strict Portal Segregation)
        $adminToResident = $this->actingAs($admin)->get(route('resident.dashboard'));
        $adminToResident->assertRedirect(route('admin.dashboard'));
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

    public function test_invalid_credentials_returns_exact_custom_error_message(): void
    {
        $response = $this->post(route('login'), [
            'email' => 'wrong.account@example.com',
            'password' => 'WrongPassword123!',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Invalid credentials. The username or password you entered is incorrect. Please check your credentials and try again.',
        ]);
        $this->assertGuest();
    }

    public function test_invalid_credentials_via_json_request_returns_custom_error_message(): void
    {
        $response = $this->postJson(route('login'), [
            'email' => 'wrong.account@example.com',
            'password' => 'WrongPassword123!',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Invalid credentials. The username or password you entered is incorrect. Please check your credentials and try again.',
        ]);
        $this->assertGuest();
    }
}
