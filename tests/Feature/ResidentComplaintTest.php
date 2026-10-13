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
}
