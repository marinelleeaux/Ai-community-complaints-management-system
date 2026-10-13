<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\NotificationPreference;
use App\Models\Purok;
use App\Services\Supabase\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function __construct(protected SupabaseStorageService $storageService)
    {
    }

    public function show()
    {
        $user = Auth::user()->load('purok');
        $puroks = Purok::where('is_active', true)->get();
        return view('resident.profile.show', compact('user', 'puroks'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'purok_id' => ['required', 'exists:puroks,id'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $avatarPath = $user->avatar_path;
        if ($request->hasFile('avatar')) {
            $upload = $this->storageService->upload('avatars', $request->file('avatar'), 'residents');
            $avatarPath = $upload['path'] ?? $avatarPath;
        }

        // Strictly prevent changing role or account_status
        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'purok_id' => $validated['purok_id'],
            'avatar_path' => $avatarPath,
        ]);

        AuditLog::log($user->id, 'updated_profile', 'users', $user->id);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function settings()
    {
        $user = Auth::user();
        $preferences = NotificationPreference::firstOrCreate(['user_id' => $user->id]);
        return view('resident.profile.settings', compact('user', 'preferences'));
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        if ($user->password && ! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'The provided current password does not match.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        AuditLog::log($user->id, 'updated_password', 'users', $user->id);

        return back()->with('success', 'Password updated successfully.');
    }

    public function updateTheme(Request $request)
    {
        $validated = $request->validate([
            'theme' => ['required', 'in:light,dark,system'],
        ]);

        $user = Auth::user();
        $user->update([
            'theme_preference' => $validated['theme'],
        ]);

        return response()->json([
            'success' => true,
            'theme' => $validated['theme'],
        ]);
    }
}
