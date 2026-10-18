<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Purok;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class NotificationManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $resident;

    protected function setUp(): void
    {
        parent::setUp();

        $purok = Purok::create(['name' => 'Purok 1', 'is_active' => true]);

        $this->admin = User::create([
            'name' => 'Admin Officer',
            'email' => 'admin@northpoblacion.gov.ph',
            'password' => Hash::make('Password123!'),
            'role' => 'admin',
            'account_status' => 'approved',
            'is_active' => true,
        ]);

        $this->resident = User::create([
            'name' => 'Maria Santos',
            'email' => 'maria@example.com',
            'password' => Hash::make('Password123!'),
            'role' => 'resident',
            'account_status' => 'approved',
            'is_active' => true,
            'purok_id' => $purok->id,
            'address' => 'Purok 1, North Poblacion',
        ]);
    }

    public function test_resident_dashboard_displays_welcome_name(): void
    {
        $response = $this->actingAs($this->resident)->get(route('resident.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Welcome, Maria Santos!');
        $response->assertDontSee('Good evening');
    }

    public function test_notification_badge_hidden_when_zero_unread(): void
    {
        // When user has no notifications
        $response = $this->actingAs($this->resident)->get(route('resident.dashboard'));
        $response->assertStatus(200);
        // Should not see the red badge or hardcoded "5" or "3"
        $response->assertDontSee('bg-rose-500 text-white text-[10px] font-extrabold flex items-center justify-center');
    }

    public function test_can_mark_notification_as_unread_and_read(): void
    {
        $notif = Notification::create([
            'recipient_id' => $this->resident->id,
            'title' => 'Complaint Status Update',
            'message' => 'Your blotter has been reviewed.',
            'type' => 'status_update',
            'is_read' => true,
            'read_at' => now(),
        ]);

        // Mark as unread
        $response = $this->actingAs($this->resident)->post(route('resident.notifications.unread', $notif->id));
        $response->assertSessionHas('success');
        $this->assertFalse($notif->fresh()->is_read);

        // Mark as read
        $response = $this->actingAs($this->resident)->post(route('resident.notifications.read', $notif->id));
        $response->assertSessionHas('success');
        $this->assertTrue($notif->fresh()->is_read);
    }

    public function test_can_mark_all_as_unread_and_read_all(): void
    {
        Notification::create([
            'recipient_id' => $this->resident->id,
            'title' => 'Notice 1',
            'message' => 'Notice 1 details',
            'type' => 'announcement',
            'is_read' => true,
            'read_at' => now(),
        ]);

        Notification::create([
            'recipient_id' => $this->resident->id,
            'title' => 'Notice 2',
            'message' => 'Notice 2 details',
            'type' => 'announcement',
            'is_read' => true,
            'read_at' => now(),
        ]);

        $this->assertEquals(0, $this->resident->unreadNotificationsCount());

        // Mark all as unread
        $this->actingAs($this->resident)->post(route('resident.notifications.unread-all'));
        $this->assertEquals(2, $this->resident->fresh()->unreadNotificationsCount());

        // Mark all as read
        $this->actingAs($this->resident)->post(route('resident.notifications.read-all'));
        $this->assertEquals(0, $this->resident->fresh()->unreadNotificationsCount());
    }

    public function test_can_delete_single_and_all_notifications(): void
    {
        $n1 = Notification::create([
            'recipient_id' => $this->admin->id,
            'title' => 'Admin Alert 1',
            'message' => 'Alert 1 details',
            'type' => 'alert',
            'is_read' => false,
        ]);

        $n2 = Notification::create([
            'recipient_id' => $this->admin->id,
            'title' => 'Admin Alert 2',
            'message' => 'Alert 2 details',
            'type' => 'alert',
            'is_read' => false,
        ]);

        // Delete single
        $this->actingAs($this->admin)->delete(route('admin.notifications.destroy', $n1->id));
        $this->assertDatabaseMissing('notifications', ['id' => $n1->id]);
        $this->assertDatabaseHas('notifications', ['id' => $n2->id]);

        // Delete all
        $this->actingAs($this->admin)->post(route('admin.notifications.delete-all'));
        $this->assertDatabaseMissing('notifications', ['id' => $n2->id]);
    }
}
