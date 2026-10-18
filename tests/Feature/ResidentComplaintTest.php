<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\Purok;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResidentComplaintTest extends TestCase
{
    use RefreshDatabase;

    protected User $resident;
    protected User $otherResident;
    protected Purok $purok;
    protected ComplaintCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->purok = Purok::create(['name' => 'Purok 3', 'is_active' => true]);
        $this->category = ComplaintCategory::create(['name' => 'Waste Management', 'is_active' => true]);

        $this->resident = User::create([
            'name' => 'Jan Marinelle Auditor',
            'email' => 'jan.auditor@gmail.com',
            'password' => Hash::make('Password123!'),
            'role' => 'resident',
            'account_status' => 'approved',
            'is_active' => true,
            'purok_id' => $this->purok->id,
            'address' => 'Purok 3, North Poblacion',
        ]);

        $this->otherResident = User::create([
            'name' => 'Carlos Dizon',
            'email' => 'carlos.dizon@example.com',
            'password' => Hash::make('Password123!'),
            'role' => 'resident',
            'account_status' => 'approved',
            'is_active' => true,
            'purok_id' => $this->purok->id,
            'address' => 'Purok 3, North Poblacion',
        ]);
    }

    public function test_resident_can_view_complaints_index(): void
    {
        $response = $this->actingAs($this->resident)->get(route('resident.complaints.index'));
        $response->assertStatus(200);
        $response->assertSee('Complaints');
    }

    public function test_resident_can_submit_complaint_successfully(): void
    {
        $response = $this->actingAs($this->resident)->post(route('resident.complaints.store'), [
            'title' => 'Daghan kaayo og basura sa kilid sa dalan',
            'purok_id' => $this->purok->id,
            'category_id' => $this->category->id,
            'incident_date' => now()->format('Y-m-d'),
            'incident_location' => 'Corner Street near Purok 3 waiting shed',
            'original_description' => 'Dugay na wala nakolekta ang basura diri sa Purok 3. Nanimahong baho na kaayo ug nagkatag sa dalan.',
        ]);

        $complaint = Complaint::where('resident_id', $this->resident->id)->first();
        $this->assertNotNull($complaint);
        $this->assertEquals('Daghan kaayo og basura sa kilid sa dalan', $complaint->title);
        $this->assertEquals('submitted', $complaint->status);

        $response->assertRedirect(route('resident.complaints.show', $complaint->id));
    }

    public function test_resident_can_view_own_complaint(): void
    {
        $complaint = Complaint::create([
            'reference_number' => 'CMP-20261013-0001',
            'resident_id' => $this->resident->id,
            'purok_id' => $this->purok->id,
            'category_id' => $this->category->id,
            'title' => 'Sirado ang dalan tungod sa nahugno nga sanga',
            'original_description' => 'Dili maagian ang dalan padulong sa crossing.',
            'incident_location' => 'Main Road, Purok 3',
            'incident_date' => now()->subDay(),
            'status' => 'in_progress',
            'final_priority' => 'high',
        ]);

        $response = $this->actingAs($this->resident)->get(route('resident.complaints.show', $complaint->id));
        $response->assertStatus(200);
        $response->assertSee('CMP-20261013-0001');
        $response->assertSee('Sirado ang dalan');
    }

    public function test_resident_cannot_view_another_residents_complaint(): void
    {
        $otherComplaint = Complaint::create([
            'reference_number' => 'CMP-20261013-0002',
            'resident_id' => $this->otherResident->id,
            'purok_id' => $this->purok->id,
            'category_id' => $this->category->id,
            'title' => 'Private Neighbor Issue',
            'original_description' => 'Confidential problem of other resident',
            'incident_location' => 'Purok 3',
            'incident_date' => now()->subDay(),
            'status' => 'submitted',
            'final_priority' => 'low',
        ]);

        $response = $this->actingAs($this->resident)->get(route('resident.complaints.show', $otherComplaint->id));
        $response->assertStatus(403);
    }

    public function test_resident_can_view_edit_page_and_update_own_complaint(): void
    {
        $complaint = Complaint::create([
            'reference_number' => 'CMP-20261013-0003',
            'resident_id' => $this->resident->id,
            'purok_id' => $this->purok->id,
            'category_id' => $this->category->id,
            'title' => 'Initial Noise Complaint',
            'original_description' => 'Karaoke noise past midnight.',
            'incident_location' => 'Purok 3',
            'incident_date' => now()->subDay(),
            'status' => 'submitted',
            'final_priority' => 'low',
        ]);

        // Can view edit form
        $response = $this->actingAs($this->resident)->get(route('resident.complaints.edit', $complaint->id));
        $response->assertStatus(200);
        $response->assertSee('Edit Complaint');
        $response->assertSee('Initial Noise Complaint');

        // Can submit update
        $updateResponse = $this->actingAs($this->resident)->put(route('resident.complaints.update', $complaint->id), [
            'title' => 'Updated Loud Videoke Party',
            'purok_id' => $this->purok->id,
            'category_id' => $this->category->id,
            'incident_date' => now()->subDay()->format('Y-m-d'),
            'incident_location' => 'Purok 3 Near Hall',
            'original_description' => 'Excessively loud music continuing through 2 AM.',
        ]);

        $updateResponse->assertRedirect(route('resident.complaints.show', $complaint->id));
        $this->assertDatabaseHas('complaints', [
            'id' => $complaint->id,
            'title' => 'Updated Loud Videoke Party',
            'incident_location' => 'Purok 3 Near Hall',
        ]);

        // Check Before and After display in show view
        $showResponse = $this->actingAs($this->resident)->get(route('resident.complaints.show', $complaint->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Complaint Edit History');
        $showResponse->assertSee('Initial Noise Complaint');
        $showResponse->assertSee('Updated Loud Videoke Party');
        $showResponse->assertSee('Before (Previous)');
        $showResponse->assertSee('After (Edited Version)');
    }

    public function test_resident_can_delete_own_submitted_complaint(): void
    {
        $complaint = Complaint::create([
            'reference_number' => 'CMP-20261013-0004',
            'resident_id' => $this->resident->id,
            'purok_id' => $this->purok->id,
            'category_id' => $this->category->id,
            'title' => 'Accidental Submission',
            'original_description' => 'Mistakenly submitted twice by user.',
            'incident_location' => 'Purok 3',
            'incident_date' => now()->subDay(),
            'status' => 'submitted',
            'final_priority' => 'low',
        ]);

        $response = $this->actingAs($this->resident)->delete(route('resident.complaints.destroy', $complaint->id));
        $response->assertRedirect(route('resident.complaints.index'));
        $this->assertDatabaseMissing('complaints', [
            'id' => $complaint->id,
        ]);
    }

    public function test_resident_cannot_edit_or_delete_resolved_complaint(): void
    {
        $complaint = Complaint::create([
            'reference_number' => 'CMP-20261013-0005',
            'resident_id' => $this->resident->id,
            'purok_id' => $this->purok->id,
            'category_id' => $this->category->id,
            'title' => 'Already Resolved Incident',
            'original_description' => 'Already taken care of by barangay officials.',
            'incident_location' => 'Purok 3',
            'incident_date' => now()->subDays(3),
            'status' => 'resolved',
            'final_priority' => 'medium',
        ]);

        // Attempt edit
        $editResponse = $this->actingAs($this->resident)->get(route('resident.complaints.edit', $complaint->id));
        $editResponse->assertStatus(403);

        // Attempt delete
        $deleteResponse = $this->actingAs($this->resident)->delete(route('resident.complaints.destroy', $complaint->id));
        $deleteResponse->assertStatus(403);
    }

    public function test_admin_cannot_edit_or_delete_resident_complaint(): void
    {
        $admin = User::create([
            'name' => 'Barangay Secretary',
            'email' => 'sec@northpoblacion.gov.ph',
            'password' => Hash::make('Password123!'),
            'role' => 'admin',
            'account_status' => 'approved',
            'is_active' => true,
        ]);

        $complaint = Complaint::create([
            'reference_number' => 'CMP-20261013-0006',
            'resident_id' => $this->resident->id,
            'purok_id' => $this->purok->id,
            'category_id' => $this->category->id,
            'title' => 'Resident Only Modify Test',
            'original_description' => 'Only the resident can modify this complaint.',
            'incident_location' => 'Purok 3',
            'incident_date' => now()->subDay(),
            'status' => 'submitted',
            'final_priority' => 'medium',
        ]);

        $this->assertFalse($admin->can('update', $complaint));
        $this->assertFalse($admin->can('delete', $complaint));
    }
}
