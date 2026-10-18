<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    protected function clearUserNotifCache(string|int $userId): void
    {
        Cache::forget("user_{$userId}_unread_notifs_count");
        Cache::forget("user_{$userId}_recent_notifs");
    }

    public function index()
    {
        $user = Auth::user();
        $notifications = Notification::where('recipient_id', $user->id)
            ->latest()
            ->paginate(15);

        return view('resident.notifications.index', compact('notifications'));
    }

    public function markAsRead(int $id)
    {
        $user = Auth::user();
        $notification = Notification::where('id', $id)
            ->where('recipient_id', $user->id)
            ->firstOrFail();

        $notification->markAsRead();
        $this->clearUserNotifCache($user->id);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read.',
                'unread_count' => $user->unreadNotificationsCount(),
            ]);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAsUnread(int $id)
    {
        $user = Auth::user();
        $notification = Notification::where('id', $id)
            ->where('recipient_id', $user->id)
            ->firstOrFail();

        $notification->markAsUnread();
        $this->clearUserNotifCache($user->id);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as unread.',
                'unread_count' => $user->unreadNotificationsCount(),
            ]);
        }

        return back()->with('success', 'Notification marked as unread.');
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        Notification::where('recipient_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        $this->clearUserNotifCache($user->id);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read.',
                'unread_count' => 0,
            ]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    public function markAllAsUnread()
    {
        $user = Auth::user();
        Notification::where('recipient_id', $user->id)
            ->update([
                'is_read' => false,
                'read_at' => null,
            ]);

        $this->clearUserNotifCache($user->id);
        $unreadCount = $user->unreadNotificationsCount();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as unread.',
                'unread_count' => $unreadCount,
            ]);
        }

        return back()->with('success', 'All notifications marked as unread.');
    }

    public function destroy(int $id)
    {
        $user = Auth::user();
        Notification::where('id', $id)
            ->where('recipient_id', $user->id)
            ->delete();

        $this->clearUserNotifCache($user->id);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully.',
                'unread_count' => $user->unreadNotificationsCount(),
            ]);
        }

        return back()->with('success', 'Notification deleted successfully.');
    }

    public function destroyAll()
    {
        $user = Auth::user();
        Notification::where('recipient_id', $user->id)->delete();

        $this->clearUserNotifCache($user->id);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications deleted.',
                'unread_count' => 0,
            ]);
        }

        return back()->with('success', 'All notifications deleted successfully.');
    }

    public function poll()
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['unread_count' => 0, 'notifications' => []]);
        }

        $unreadCount = Cache::remember("user_{$user->id}_unread_notifs_count", 15, fn () => $user->unreadNotificationsCount());
        $latest = Cache::remember("user_{$user->id}_recent_notifs", 15, function () use ($user) {
            return Notification::where('recipient_id', $user->id)
                ->latest()
                ->take(8)
                ->get();
        });

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $latest->map(fn ($n) => [
                'id' => $n->id,
                'title' => $n->title,
                'message' => $n->message,
                'is_read' => $n->is_read,
                'created_at' => $n->created_at->diffForHumans(),
            ]),
        ]);
    }
}
