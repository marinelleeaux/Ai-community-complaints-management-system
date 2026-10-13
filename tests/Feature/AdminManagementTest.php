<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\Purok;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $pendingResident;
    protected Purok $purok;
    protected ComplaintCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->purok = Purok::create(['name' => 'Purok 1', 'is_active' => true]);
        $this->category = ComplaintCategory::create(['name' => 'Roads & Infrastructure', 'is_active' => true]);

        $this->admin = User::create([
            'name' => 'Admin Officer',
            'email' => 'admin@northpoblacion.gov.ph',
            'password' => Hash::make('Password123!'),
            'role' => 'admin',
            'account_status' => 'approved',
            'is_active' => true,
        ]);

        $this->pendingResident = User::create([
            'name' => 'Applicant Resident',
            'email' => 'applicant@example.com',
            'password' => Hash::make('Password123!'),
            'role' => 'resident',
            'account_status' => 'pending',
            'is_active' => true,
            'purok_id' => $this->purok->id,
            'address' => 'Purok 1, North Poblacion',
        ]);
    }

    public function test_admin_can_view_complaints_queue(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.complaints.index'));
        $response->assertStatus(200);
        $response->assertSee('Complaints');
    }

    public function test_admin_can_update_complaint_status_and_add_internal_note(): void
    {
        $complaint = Complaint::create([
            'reference_number' => 'CMP-20261013-0005',
            'resident_id' => $this->pendingResident->id,
            'purok_id' => $this->purok->id,
            'category_id' => $this->category->id,
            'title' => 'Guba nga suga sa poste',
            'original_description' => 'Nangitngit ang kalsada.',
            'incident_location' => 'Purok 1',
            'incident_date' => now(),
            'status' => 'submitted',
            'final_priority' => 'medium',
        ]);

        // Status update
        $statusResponse = $this->actingAs($this->admin)->put(route('admin.complaints.update-status', $complaint->id), [
            'status' => 'in_progress',
            'public_remarks' => 'Barangay electrician team dispatched.',
        ]);
        $statusResponse->assertSessionHas('success');

        $this->assertDatabaseHas('complaints', [
            'id' => $complaint->id,
            'status' => 'in_progress',
            'resident_visible_remarks' => 'Barangay electrician team dispatched.',
        ]);

        // Internal note
        $noteResponse = $this->actingAs($this->admin)->post(route('admin.complaints.add-note', $complaint->id), [
            'note_content' => 'Spoke to Engr. Reyes regarding replacement bulbs.',
        ]);
        $noteResponse->assertSessionHas('success');

        $this->assertDatabaseHas('complaint_internal_notes', [
            'complaint_id' => $complaint->id,
            'note_content' => 'Spoke to Engr. Reyes regarding replacement bulbs.',
        ]);
    }

    public function test_admin_can_approve_pending_resident(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.residents.approve', $this->pendingResident->id), [
            'remarks' => 'Verified resident ID with Purok Leader.',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $this->pendingResident->id,
            'account_status' => 'approved',
        ]);
    }

    public function test_admin_can_toggle_purok_status(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.settings.toggle-purok', $this->purok->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('puroks', [
            'id' => $this->purok->id,
            'is_active' => false,
        ]);
    }

    public function test_admin_can_export_complaints_csv(): void
    {
        Complaint::create([
            'reference_number' => 'CMP-20261013-0006',
            'resident_id' => $this->pendingResident->id,
            'purok_id' => $this->purok->id,
            'category_id' => $this->category->id,
            'title' => 'Test CSV Export Entry',
            'original_description' => 'Testing CSV export stream',
            'incident_location' => 'Purok 1',
            'incident_date' => now(),
            'status' => 'submitted',
            'final_priority' => 'low',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports.export'));
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'text/csv'));
    }

    public function test_admin_can_view_complaints_on_dashboard(): void
    {
        Complaint::create([
            'reference_number' => 'CMP-20261013-0007',
            'resident_id' => $this->pendingResident->id,
            'purok_id' => $this->purok->id,
            'category_id' => $this->category->id,
            'title' => 'Flooding along Purok 1 drainage',
            'original_description' => 'Grabe ang baha kay barado ang kanal.',
            'incident_location' => 'Purok 1',
            'incident_date' => now(),
            'status' => 'submitted',
            'final_priority' => 'urgent',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Flooding along Purok 1 drainage');
        $response->assertSee('CMP-20261013-0007');
    }

    public function test_admin_can_manually_resolve_pending_complaint(): void
    {
        $complaint = Complaint::create([
            'reference_number' => 'CMP-20261013-0008',
            'resident_id' => $this->pendingResident->id,
            'purok_id' => $this->purok->id,
            'category_id' => $this->category->id,
            'title' => 'Burst water pipe',
            'original_description' => 'Nibuswak ang tubo.',
            'incident_location' => 'Purok 1',
            'incident_date' => now(),
            'status' => 'submitted',
            'final_priority' => 'high',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.complaints.update-status', $complaint->id), [
            'status' => 'resolved',
            'public_remarks' => 'Pipe repaired and cleared by barangay plumber.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('complaints', [
            'id' => $complaint->id,
            'status' => 'resolved',
            'resident_visible_remarks' => 'Pipe repaired and cleared by barangay plumber.',
        ]);
        $this->assertNotNull($complaint->fresh()->resolved_at);
    }

    public function test_admin_can_manually_reject_pending_complaint(): void
    {
        $complaint = Complaint::create([
            'reference_number' => 'CMP-20261013-0009',
            'resident_id' => $this->pendingResident->id,
            'purok_id' => $this->purok->id,
            'category_id' => $this->category->id,
            'title' => 'Duplicate noise report',
            'original_description' => 'Saba ang silingan.',
            'incident_location' => 'Purok 1',
            'incident_date' => now(),
            'status' => 'submitted',
            'final_priority' => 'low',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.complaints.update-status', $complaint->id), [
            'status' => 'rejected',
            'public_remarks' => 'Duplicate report already addressed in ticket CMP-20261013-0005.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('complaints', [
            'id' => $complaint->id,
            'status' => 'rejected',
            'resident_visible_remarks' => 'Duplicate report already addressed in ticket CMP-20261013-0005.',
        ]);
    }
}
