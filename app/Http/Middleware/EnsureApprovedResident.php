<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureApprovedResident
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Admins cannot access the residents portal (Strict Portal Segregation)
        if ($user->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Access denied: Administrators cannot access the Residents Portal.'], 403);
            }
            return redirect()->route('admin.dashboard')->with('error', 'Access restricted: Administrators cannot access the Residents Portal. Please use the Admin Command Center.');
        }

        if (! $user->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('access-denied')->with('error', 'Your resident account has been deactivated.');
        }

        if ($user->isRejected()) {
            return redirect()->route('registration-rejected');
        }

        if ($user->isPending()) {
            return redirect()->route('pending-approval');
        }

        if (! $user->isApproved()) {
            return redirect()->route('pending-approval');
        }

        return $next($request);
    }
}
