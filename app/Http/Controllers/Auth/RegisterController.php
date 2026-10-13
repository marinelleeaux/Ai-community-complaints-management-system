<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Notification;
use App\Models\NotificationPreference;
use App\Models\Purok;
use App\Models\ResidentVerificationRequest;
use App\Models\User;
use App\Services\Supabase\SupabaseAuthService;
use App\Services\Supabase\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function __construct(
        protected SupabaseAuthService $authService,
        protected SupabaseStorageService $storageService
    ) {
    }

    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect()->route('resident.dashboard');
        }

        $puroks = Purok::where('is_active', true)->orderBy('name')->get();
        return view('auth.register', compact('puroks'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'purok_id' => ['required', 'exists:puroks,id'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'privacy_agreement' => ['accepted'],
        ], [
            'privacy_agreement.accepted' => 'You must agree to the Data Privacy Notice to proceed with registration.',
        ]);

        $email = trim(strtolower($validated['email']));
        $password = $validated['password'];

        // 1. Create Supabase Auth identity (with auto-confirm; no verification email needed)
        $signUpResult = $this->authService->signUp($email, $password, [
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'role' => 'resident',
        ]);

        if (! $signUpResult['success']) {
            throw ValidationException::withMessages([
                'email' => [$signUpResult['error'] ?? 'Could not create account in authentication provider.'],
            ]);
        }

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $upload = $this->storageService->upload('avatars', $request->file('avatar'), 'residents');
            $avatarPath = $upload['path'] ?? null;
        }

        // 2. Database transaction to create User profile and Verification Request
        $user = DB::transaction(function () use ($validated, $email, $password, $signUpResult, $avatarPath) {
            $userId = $signUpResult['id'] ?? (string) Str::uuid();

            $newUser = User::create([
                'id' => $userId,
                'name' => $validated['name'],
                'email' => $email,
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'purok_id' => $validated['purok_id'],
                'role' => 'resident',
                'account_status' => 'pending', // Requires admin approval
                'avatar_path' => $avatarPath,
                'theme_preference' => 'system',
                'supabase_uid' => $signUpResult['id'] ?? null,
                'password' => Hash::make($password),
            ]);

            // Create verification request record
            ResidentVerificationRequest::create([
                'resident_id' => $newUser->id,
                'verification_status' => 'pending',
                'submitted_at' => now(),
            ]);

            // Create notification preferences
            NotificationPreference::create([
                'user_id' => $newUser->id,
                'in_app_enabled' => true,
                'email_enabled' => true,
            ]);

            // Notify all admins about new resident registration
            $admins = User::whereIn('role', ['admin', 'super_admin'])->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'recipient_id' => $admin->id,
                    'title' => 'New Resident Registration: ' . $newUser->name,
                    'message' => "A resident registered from {$newUser->address}. Pending administrator approval.",
                    'type' => 'new_resident_registration',
                    'related_record_type' => 'user',
                    'related_record_id' => (string) $newUser->id,
                ]);
            }

            AuditLog::log($newUser->id, 'registered', 'users', $newUser->id, [
                'name' => $newUser->name,
                'email' => $newUser->email,
            ]);

            return $newUser;
        });

        // 3. Log in resident and redirect to pending approval
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('pending-approval')->with('status', 'Registration submitted successfully. Your account is pending review by Barangay Officials.');
    }
}
