<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\Supabase\SupabaseAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function __construct(protected SupabaseAuthService $authService)
    {
    }

    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = trim(strtolower($request->email));

        $user = User::where('email', $email)->first();
        if ($user) {
            $redirectTo = route('password.reset', ['token' => 'sb_recovery']);
            $this->authService->sendPasswordReset($email, $redirectTo);
            AuditLog::log($user->id, 'requested_password_reset', 'users', $user->id);
        }

        return back()->with('status', 'If an account matches that email address, password reset instructions have been dispatched.');
    }
}
