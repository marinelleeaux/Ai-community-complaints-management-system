<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\Purok;
use App\Models\User;
use App\Services\AI\ComplaintAnalysisService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BarangayAssistantTest extends TestCase
{
    use RefreshDatabase;

    protected User $resident;
    protected Purok $purok;
    protected ComplaintCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->purok = Purok::create(['name' => 'Purok 1', 'is_active' => true]);
        $this->category = ComplaintCategory::create(['name' => 'Waste Management', 'is_active' => true]);

        $this->resident = User::create([
            'name' => 'Jan Marinelle Auditor',
            'email' => 'jan.auditor@gmail.com',
            'password' => Hash::make('Password123!'),
            'role' => 'resident',
            'account_status' => 'approved',
            'is_active' => true,
            'purok_id' => $this->purok->id,
            'address' => 'Purok 1, North Poblacion',
        ]);
    }

    public function test_resident_can_view_chatbot_page(): void
    {
        $response = $this->actingAs($this->resident)->get(route('resident.chatbot'));
        $response->assertStatus(200);
        $response->assertSee('North Poblacion AI Barangay Assistant');
    }

    public function test_resident_can_send_message_to_chatbot_and_receive_response(): void
    {
        $response = $this->actingAs($this->resident)->post(route('resident.chatbot.send'), [
            'message' => 'Unsaon nako pag-reklamo sa basura diri sa among purok?',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'reply',
            'session_id',
        ]);

        $this->assertDatabaseHas('chat_messages', [
            'sender_type' => 'user',
            'message' => 'Unsaon nako pag-reklamo sa basura diri sa among purok?',
        ]);

        $this->assertDatabaseHas('chat_messages', [
            'sender_type' => 'assistant',
        ]);
    }

    public function test_ai_rule_based_fallback_analyzes_bisaya_complaint_properly(): void
    {
        $complaint = Complaint::create([
            'reference_number' => 'CMP-20261013-0099',
            'resident_id' => $this->resident->id,
            'purok_id' => $this->purok->id,
            'category_id' => $this->category->id,
            'title' => 'Dugay na kaayo ang basura sa Purok 3 baho kaayo',
            'original_description' => 'Daghan na kayo og langaw ug basura sa kilid sa kalsada wala gihakot sa basurero.',
            'incident_location' => 'Purok 3',
            'incident_date' => now(),
            'status' => 'submitted',
        ]);

        $analysisService = app(ComplaintAnalysisService::class);
        $result = $analysisService->analyze($complaint);

        $this->assertStringContainsString('Bisaya', $result['detected_language']);
        $this->assertNotEmpty($result['english_translation']);
        $this->assertNotEmpty($result['english_summary']);
        $this->assertNotEmpty($result['suggested_category']);
        $this->assertContains($result['suggested_priority'], ['low', 'medium', 'high', 'urgent']);
    }
}
