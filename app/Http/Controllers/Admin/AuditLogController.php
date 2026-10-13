<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('actor');

        if ($request->filled('action')) {
            $query->where('action', 'LIKE', "%{$request->action}%");
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('action', 'LIKE', "%{$search}%")
                    ->orWhere('entity_type', 'LIKE', "%{$search}%")
                    ->orWhere('entity_id', 'LIKE', "%{$search}%")
                    ->orWhereHas('actor', fn ($aq) => $aq->where('name', 'LIKE', "%{$search}%"));
            });
        }

        $logs = $query->latest()->paginate(20)->withQueryString();

        return view('admin.audit-logs.index', compact('logs'));
    }
}
