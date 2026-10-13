<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\Supabase\SupabaseAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __construct(protected SupabaseAuthService $authService)
    {
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $email = trim(strtolower($credentials['email']));
        $password = $credentials['password'];
        $remember = $request->boolean('remember');

        // 1. Authenticate against Supabase Auth (or local DB fallback)
        $authResult = $this->authService->signIn($email, $password);

        if (! $authResult['success']) {
            throw ValidationException::withMessages([
                'email' => [$authResult['error'] ?? 'These credentials do not match our records.'],
            ]);
        }

        // 2. Fetch or sync user in local database
        $user = User::where('email', $email)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => ['No registered profile found for this account. Please register first.'],
            ]);
        }

        // Store Supabase user ID if available
        if (! empty($authResult['user_id']) && $user->supabase_uid !== $authResult['user_id']) {
            $user->update(['supabase_uid' => $authResult['user_id']]);
        }

        // 3. Log user into Laravel session
        Auth::login($user, $remember);
        $request->session()->regenerate();

        AuditLog::log($user->id, 'login', 'users', $user->id, [
            'method' => $authResult['is_local'] ?? false ? 'local' : 'supabase',
        ]);

        return $this->redirectBasedOnRole($user);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            AuditLog::log(Auth::id(), 'logout', 'users', Auth::id());
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    protected function redirectBasedOnRole(User $user)
    {
        if ($user->isAdmin() || $user->isOfficial()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        if (! $user->isActive()) {
            return redirect()->route('access-denied');
        }

        if ($user->isRejected()) {
            return redirect()->route('registration-rejected');
        }

        if ($user->isPending()) {
            return redirect()->route('pending-approval');
        }

        return redirect()->intended(route('resident.dashboard'));
    }
}
