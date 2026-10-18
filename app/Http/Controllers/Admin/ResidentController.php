<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Notification;
use App\Models\Purok;
use App\Models\ResidentVerificationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResidentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'resident')
            ->where('account_status', '!=', 'pending')
            ->with('purok');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%")
                    ->orWhere('address', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'pending') {
            $query->where('account_status', $request->status);
        }

        if ($request->filled('purok_id')) {
            $query->where('purok_id', $request->purok_id);
        }

        if (empty($request->all())) {
            $residents = \Illuminate\Support\Facades\Cache::remember('admin_residents_index_default', \App\Services\PerformanceCache::FEEDS_TTL, function () {
                return User::where('role', 'resident')
                    ->where('account_status', '!=', 'pending')
                    ->with('purok')
                    ->latest()
                    ->paginate(15);
            });
        } else {
            $residents = $query->latest()->paginate(15)->withQueryString();
        }

        $puroks = Purok::getActiveCached();

        return view('admin.residents.index', compact('residents', 'puroks'));
    }

    public function pending()
    {
        $residents = \Illuminate\Support\Facades\Cache::remember('admin_residents_pending_default', \App\Services\PerformanceCache::FEEDS_TTL, function () {
            return User::where('role', 'resident')
                ->where('account_status', 'pending')
                ->with(['purok', 'verificationRequests'])
                ->latest()
                ->paginate(15);
        });

        return view('admin.residents.pending', compact('residents'));
    }

    public function show(string $id)
    {
        $resident = User::where('role', 'resident')
            ->with(['purok', 'complaints.category', 'verificationRequests.reviewer'])
            ->findOrFail($id);

        return view('admin.residents.show', compact('resident'));
    }

    public function approve(string $id)
    {
        $admin = Auth::user();
        $resident = User::where('role', 'resident')->findOrFail($id);

        if ($resident->isApproved()) {
            return back()->with('info', 'This resident is already approved.');
        }

        DB::transaction(function () use ($admin, $resident) {
            $resident->update([
                'account_status' => 'approved',
                'approved_at' => now(),
                'approved_by' => $admin->id,
                'rejection_reason' => null,
            ]);

            // Update verification request
            $request = ResidentVerificationRequest::where('resident_id', $resident->id)
                ->where('verification_status', 'pending')
                ->latest()
                ->first();

            if ($request) {
                $request->update([
                    'verification_status' => 'approved',
                    'reviewed_by' => $admin->id,
                    'reviewed_at' => now(),
                ]);
            }

            // Notify resident
            Notification::create([
                'recipient_id' => $resident->id,
                'title' => 'Account Approved!',
                'message' => 'Your Barangay North Poblacion resident account has been verified and approved. You can now submit complaints.',
                'type' => 'registration_approved',
                'related_record_type' => 'user',
                'related_record_id' => (string) $resident->id,
            ]);

            AuditLog::log($admin->id, 'approved_resident', 'users', $resident->id, [
                'resident_name' => $resident->name,
                'resident_email' => $resident->email,
            ]);
        });

        \App\Services\PerformanceCache::clearAdminDashboard();
        \App\Services\PerformanceCache::clearResident($resident->id);

        return back()->with('success', "Resident {$resident->name} has been approved successfully.");
    }

    public function reject(Request $request, string $id)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'min:5', 'max:500'],
        ]);

        $admin = Auth::user();
        $resident = User::where('role', 'resident')->findOrFail($id);

        DB::transaction(function () use ($admin, $resident, $request) {
            $resident->update([
                'account_status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
            ]);

            // Update verification request
            $verificationRequest = ResidentVerificationRequest::where('resident_id', $resident->id)
                ->latest()
                ->first();

            if ($verificationRequest) {
                $verificationRequest->update([
                    'verification_status' => 'rejected',
                    'reviewed_by' => $admin->id,
                    'review_reason' => $request->rejection_reason,
                    'reviewed_at' => now(),
                ]);
            }

            // Notify resident
            Notification::create([
                'recipient_id' => $resident->id,
                'title' => 'Registration Application Update',
                'message' => "Your resident application was reviewed: {$request->rejection_reason}",
                'type' => 'registration_rejected',
                'related_record_type' => 'user',
                'related_record_id' => (string) $resident->id,
            ]);

            AuditLog::log($admin->id, 'rejected_resident', 'users', $resident->id, [
                'resident_name' => $resident->name,
                'reason' => $request->rejection_reason,
            ]);
        });

        \App\Services\PerformanceCache::clearAdminDashboard();
        \App\Services\PerformanceCache::clearResident($resident->id);

        return back()->with('success', "Resident registration for {$resident->name} has been marked as rejected.");
    }

    public function toggleActive(string $id)
    {
        $admin = Auth::user();
        $resident = User::where('role', 'resident')->findOrFail($id);

        if ($admin->id === $resident->id) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $newStatus = $resident->account_status === 'inactive' ? 'approved' : 'inactive';
        $resident->update(['account_status' => $newStatus]);

        AuditLog::log($admin->id, 'toggled_resident_status', 'users', $resident->id, [
            'new_status' => $newStatus,
        ]);

        \App\Services\PerformanceCache::clearAdminDashboard();
        \App\Services\PerformanceCache::clearResident($resident->id);

        return back()->with('success', "Resident status changed to {$newStatus}.");
    }

    public function destroy(string $id)
    {
        $admin = Auth::user();
        $resident = User::where('role', 'resident')->findOrFail($id);

        if ($admin->id === $resident->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        DB::transaction(function () use ($admin, $resident) {
            $name = $resident->name;
            $email = $resident->email;

            Notification::where('recipient_id', $resident->id)->delete();
            ResidentVerificationRequest::where('resident_id', $resident->id)->delete();

            $resident->delete();

            AuditLog::log($admin->id, 'deleted_resident_account', 'users', $resident->id, [
                'resident_name' => $name,
                'resident_email' => $email,
            ]);
        });

        \App\Services\PerformanceCache::clearAdminDashboard();
        \App\Services\PerformanceCache::clearResident($resident->id);

        return redirect()->route('admin.residents.index')
            ->with('success', "Resident account for {$resident->name} has been permanently deleted.");
    }
}
