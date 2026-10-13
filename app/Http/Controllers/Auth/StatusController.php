<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
    public function pendingApproval()
    {
        $user = Auth::user();
        if ($user && $user->isApproved()) {
            return redirect()->route('resident.dashboard');
        }

        return view('auth.pending-approval', compact('user'));
    }

    public function registrationRejected()
    {
        $user = Auth::user();
        return view('auth.registration-rejected', compact('user'));
    }

    public function accessDenied()
    {
        return view('auth.access-denied');
    }
}
