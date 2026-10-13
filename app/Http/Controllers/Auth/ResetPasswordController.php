<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, ?string $token = null)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::where('email', trim(strtolower($request->email)))->first();
        if ($user) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
            AuditLog::log($user->id, 'password_reset_completed', 'users', $user->id);

            return redirect()->route('login')->with('success', 'Your password has been reset successfully. Please log in.');
        }

        return back()->withErrors(['email' => 'Unable to reset password for this email address.']);
    }
}
